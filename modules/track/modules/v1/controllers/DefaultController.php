<?php

namespace app\modules\track\modules\v1\controllers;

use app\modules\track\modules\v1\models\Track;
use app\rest\AbstractController;
use Yii;
use yii\base\InvalidConfigException;

/**
 *
 * @property-read string $modelSearch
 */
class DefaultController extends AbstractController
{
    /**
     * @param array $config
     * @return Track
     * @throws InvalidConfigException
     */
    protected function getModel(array $config = []): Track
    {
        return Yii::createObject(Track::class, $config);
    }

    /**
     * @return string
     */
    protected function getModelSearch(): string
    {
        return Track::class;
    }

    /**
     * @return array
     */
    protected function getExportAttribute(): array
    {
        return [];
    }
}
