<?php

namespace app\modules\requests\models;

use app\common\db\ActiveRecord;
use app\models\User;
use app\models\query\UserQuery;
use app\modules\requests\interface\StatusRequestInterface;
use app\modules\requests\models\query\RequestQuery;
use app\modules\requests\traits\StatusRequestTrait;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%request}}".
 *
 * @property string $id ID
 * @property string $name Имя
 * @property string $email Email
 * @property string $message Сообщение
 * @property string|null $comment Комментарий
 * @property int $status
 * @property string|null $idModerator Модератор
 * @property string $createdAt Создано
 * @property string $updatedAt Обновлено
 *
 * @property User $moderatorRelation
 */
class Request extends ActiveRecord implements StatusRequestInterface
{
    use StatusRequestTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%request}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'email', 'message',], 'required'],
            [['message', 'comment'], 'string'],
            [['comment'], 'default', 'value' => null],
            [
                ['comment'],
                'required',
                'when' => function (Request $model) {
                    return $model->status === self::STATUS_RESOLVE;
                },
            ],
            [
                ['comment'],
                'string',
                'min' => 2,
                'when' => function (Request $model) {
                    return $model->status === self::STATUS_RESOLVE;
                },
            ],
            [['createdAt', 'updatedAt'], 'safe'],
            ['email', 'email',],
            [['name', 'email'], 'string', 'max' => 255],
            [
                ['idModerator'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['idModerator' => 'id']
            ],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [
                'status',
                'in',
                'range' => array_keys(self::getStatusList())
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
            'name' => 'Имя',
            'email' => 'Email',
            'message' => 'Сообщение',
            'comment' => 'Комментарий',
            'status' => 'Статус',
            'idModerator' => 'Модератор',
            'createdAt' => 'Создано',
            'updatedAt' => 'Обновлено',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getModeratorRelation(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'idModerator']);
    }

    /**
     * @return RequestQuery
     */
    public static function find(): RequestQuery
    {
        return new RequestQuery(get_called_class());
    }
}
