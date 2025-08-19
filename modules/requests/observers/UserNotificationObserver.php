<?php

namespace app\modules\requests\observers;

use SplObserver;
use SplSubject;
use Yii;

class UserNotificationObserver implements SplObserver
{
    /**
     * @param SplSubject $subject
     * @return void
     */
    public function update(SplSubject $subject): void
    {
        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['emailNotification'])
            ->setTo($subject->getEmail())
            ->setSubject($subject->getSubject())
            ->setTextBody($subject->getMessage())
            ->setHtmlBody('<b>' . $subject->getMessage() . '</b>')
            ->send();
    }
}
