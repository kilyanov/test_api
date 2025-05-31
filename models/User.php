<?php

namespace app\models;

use app\common\db\ActiveRecord;
use app\common\db\traits\StatusAttributeTrait;
use app\common\interface\StatusAttributeInterface;
use app\models\query\UserQuery;
use ext\behaviors\GenerateRandomStringBehavior;
use Lcobucci\Clock\SystemClock;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $id ID
 * @property string $username Логин
 * @property string|null $unitId Подразделение
 * @property string $auth_key Ключ
 * @property string $password_hash Пароль
 * @property string $email Email
 * @property string|null $accessToken Токен доступа
 * @property int $status
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property-write mixed $password
 * @property-read string $authKey
 *
 */
class User extends ActiveRecord implements IdentityInterface, StatusAttributeInterface
{
    use StatusAttributeTrait;

    const SCENARIO_CREATE = 'create';

    const SCENARIO_REFRESH_TOKEN = 'refresh_token';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * @return array
     */
    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        return ArrayHelper::merge(
            $behaviors,
            [
                'GenerateRandomStringBehaviorAccessToken' => [
                    'class' => GenerateRandomStringBehavior::class,
                    'skipUpdateOnClean' => false,
                    'attribute' => 'accessToken',
                    'stringLength' => 128,
                    'scenarios' => [
                        self::SCENARIO_CREATE,
                        self::SCENARIO_REFRESH_TOKEN,
                    ],
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['unitId', 'accessToken'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 1],
            [['username', 'auth_key', 'password_hash', 'email',], 'required'],
            [['status'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['username', 'unitId', 'password_hash', 'email',], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['id'], 'unique'],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [
                ['auth_key', 'accessToken'],
                'string',
                'max' => 128,
                'on' => [
                    self::SCENARIO_CREATE,
                    self::SCENARIO_REFRESH_TOKEN,
                ],
            ],
            [
                ['auth_key', 'accessToken'],
                'unique',
                'on' => [
                    self::SCENARIO_CREATE,
                    self::SCENARIO_REFRESH_TOKEN,
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'unitId' => 'Подразделение',
            'auth_key' => 'Ключ',
            'password_hash' => 'Пароль',
            'email' => 'Email',
            'accessToken' => 'Токен доступа',
            'status' => 'Статус',
            'createdAt' => 'Создано',
            'updatedAt' => 'Обновлено',
        ];
    }

    /**
     * @return UserQuery
     */
    public static function find(): UserQuery
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * @param $authKey
     * @return bool
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param $password
     * @return bool
     */
    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @throws Exception
     */
    public function setPassword($password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @throws Exception
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @param $id
     * @return User|IdentityInterface|null
     */
    public static function findIdentity($id): User|IdentityInterface|null
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param $token
     * @param $type
     * @return ActiveRecord|IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null): ActiveRecord|IdentityInterface|null
    {
        return static::find()->where([
            static::tableName() . '.[[accessToken]]' => $token,
        ])->blocked()->one();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
