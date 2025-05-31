<?php

declare(strict_types=1);

namespace ext\behaviors;

use Symfony\Component\Uid\Uuid;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

class IdAttributeBehavior extends AttributeBehavior
{
    public const TYPE_INTEGER = 0;
    public const TYPE_UUID_V6 = 6;

    public const ATTRIBUTE_NAME = 'id';

    public function __construct(
        public readonly int $typePrimary = self::TYPE_UUID_V6,
        public readonly string $attributeName = self::ATTRIBUTE_NAME,
        $config = []
    )
    {
        parent::__construct($config);
    }

    /**
     * @return void
     */
    public function init(): void
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->attributeName],
            ];
        }
    }

    /**
     * @param $event
     * @return string|null
     */
    protected function getValue($event): ?string
    {
        if (empty($this->value)) {
            switch ($this->typePrimary) {
                case self::TYPE_INTEGER:
                    return null;
                case self::TYPE_UUID_V6:
                    return Uuid::v6()->__toString();
            }
        }
    }
}
