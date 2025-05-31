<?php

namespace ext\swagger\assets;

use yii\web\AssetBundle;

class UiAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bower/swagger-ui/dist';

    /**
     * @var array
     */
    public $css = [
        'swagger-ui.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'swagger-ui-bundle.js',
        'swagger-ui-standalone-preset.js',
    ];
}
