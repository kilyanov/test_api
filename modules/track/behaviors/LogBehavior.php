<?php

namespace app\modules\track\behaviors;

use app\modules\track\models\Track;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;

/**
 *
 * @property-write mixed $log
 * @property-write mixed $logUpdate
 */
class LogBehavior extends Behavior
{
    /**
     * @var Logger|null
     */
    protected ?Logger $logger = null;
    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $fileLog = Yii::getAlias('@runtime/track.log');
        if (file_exists($fileLog) && filesize($fileLog) > 200 * 1024 * 1024) {
            @unlink($fileLog);
        }
        $this->logger = new Logger('track-log');
        $this->logger->pushHandler(new StreamHandler($fileLog));
        parent::__construct($config);
    }

    /**
     * @return string[]
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'setLog',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'setLogUpdate',
        ];
    }

    /**
     * @param $event
     * @return void
     * @throws InvalidConfigException
     */
    public function setLog($event): void
    {
        $sender = $event->sender;
        $message = sprintf(
            "Создание трекера ID: %s, трекер: %s, статус: %s, время: %s",
            $sender->id,
            $sender->track_number,
            $sender->getStatus(),
            $sender->createdAt
        );
        $this->logger->info($message);
    }

    /**
     * @param $event
     * @return void
     * @throws InvalidConfigException
     */
    public function setLogUpdate($event): void
    {
        $sender = $event->sender;
        $message = sprintf(
            "Изменение трекера ID: %s, трекер: %s, старый статус: %s, новый статус: %s, время: %s",
            $sender->id,
            $sender->track_number,
            Track::getStatusList()[$sender->getOldAttribute('status')],
            $sender->getStatus(),
            $sender->updatedAt
        );
        $this->logger->info($message);
    }
}