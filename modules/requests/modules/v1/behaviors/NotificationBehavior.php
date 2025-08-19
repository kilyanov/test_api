<?php

declare(strict_types=1);

namespace app\modules\requests\modules\v1\behaviors;

use app\modules\requests\interface\StatusRequestInterface;
use app\modules\requests\modules\v1\models\Request;
use app\modules\requests\RequestNotificationServer;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;

class NotificationBehavior extends Behavior
{
    /**
     * @param RequestNotificationServer $notificationServer
     * @param array $config
     */
    public function __construct(
        protected RequestNotificationServer $notificationServer,
        array                               $config = []
    )
    {
        parent::__construct($config);
    }

    /**
     * @return string[]
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'notify',
        ];
    }

    /**
     * @param $event
     * @return void
     */
    public function notify($event): void
    {
        /** @var Request $owner */
        $owner = $this->owner;
        if ($owner->status === StatusRequestInterface::STATUS_RESOLVE) {
            $this->notificationServer
                ->setSubject('Уведомление об изменении статуса обращения.')
                ->setEmail($owner->email)
                ->setMessage($owner->comment)
                ->notify();
        }
    }
}
