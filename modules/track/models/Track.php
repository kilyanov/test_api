<?php

declare(strict_types=1);

namespace app\modules\track\models;

use app\common\db\ActiveRecord;
use app\modules\track\behaviors\LogBehavior;
use app\modules\track\trait\StatusAttributeTrait;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%track}}".
 *
 * @property string $id ID
 * @property string $track_number Номер трека
 * @property int|null $status Статус
 * @property string $createdAt
 * @property string $updatedAt
 */
class Track extends ActiveRecord
{
    use StatusAttributeTrait;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        return ArrayHelper::merge(
            $behaviors, [
            'LogBehavior' => [
                'class' => LogBehavior::class,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%track}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['track_number',], 'required'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['status', 'track_number'], 'string', 'max' => 255],
            [['track_number'], 'unique'],
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
            'track_number' => 'Номер трека',
            'status' => 'Статус',
            'createdAt' => 'Создано',
            'updatedAt' => 'Обновлено',
        ];
    }
}
