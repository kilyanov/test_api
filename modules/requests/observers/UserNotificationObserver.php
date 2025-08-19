<?php

namespace app\modules\requests\observers;

use SplObserver;
use SplSubject;

class UserNotificationObserver implements SplObserver
{
    /**
     * @param SplSubject $subject
     * @return void
     */
    public function update(SplSubject $subject): void
    {
        // TODO: Implement update() method.
    }
}
