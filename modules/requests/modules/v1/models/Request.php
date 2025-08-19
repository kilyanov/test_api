<?php

declare(strict_types=1);

namespace app\modules\requests\modules\v1\models;

use app\modules\requests\models\Request as RequestAlias;
use app\modules\requests\modules\v1\behaviors\NotificationBehavior;
use DateTime;
use yii\helpers\ArrayHelper;

class Request extends RequestAlias
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['notificationBehavior'] = [
            'class' => NotificationBehavior::class,
        ];
        return $behaviors;
    }

    public function rules(): array
    {
        $rules = parent::rules();
        return ArrayHelper::merge(
            $rules,
            [
                [['createdAt', 'updatedAt'], 'date', 'format' => 'php:Y-m-d H:i:s'],
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
            ]
        );
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'id',
            'name',
            'email',
            'message',
            'comment',
            'status',
            'idModerator',
            'createdAt' => function () {
                return (new DateTime($this->createdAt))->format('d.m.Y H:i:s');
            },
            'updatedAt' => function () {
                return (new DateTime($this->updatedAt))->format('d.m.Y H:i:s');
            },
        ];
    }

    /**
     * @return string[]
     */
    public function extraFields(): array
    {
        return [
            'moderator' => 'moderatorRelation',
        ];
    }
}
