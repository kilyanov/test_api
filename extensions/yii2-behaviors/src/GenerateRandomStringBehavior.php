<?php

namespace ext\behaviors;

use Yii;
use yii\base\Event;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

class GenerateRandomStringBehavior extends AttributeBehavior
{
    /**
     * @var string|null
     */
    public ?string $attribute = null;

    /**
     * @var int
     */
    public int $stringLength = 128;

    /**
     * @var array
     */
    public array $scenarios = [];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->attribute === null) {
            throw new InvalidConfigException('The "attribute" property must be set.');
        }

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->attribute],
                BaseActiveRecord::EVENT_BEFORE_UPDATE => [$this->attribute],
            ];
        }
    }

    /**
     * @return array
     */
    public function events(): array
    {
        return ArrayHelper::merge(parent::events(), [
            Model::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ]);
    }

    /**
     * @param Event $event
     */
    public function beforeValidate(Event $event): void
    {
        $sender = $event->sender;

        if (!in_array($sender->getScenario(), $this->scenarios)) {
            $this->attributes = [];
        }
    }

    /**
     * @param Event $event
     *
     * @return mixed|string
     * @throws Exception
     */
    protected function getValue($event)
    {
        return is_callable($this->value) ? call_user_func($this->value,
            $event) : Yii::$app->getSecurity()->generateRandomString($this->stringLength);
    }
}
