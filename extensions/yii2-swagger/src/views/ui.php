<?php

use ext\swagger\assets\UiAsset;
use yii\helpers\Json;
use yii\web\View;

/** @var $this View */
/** @var $urls array */

UiAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title>Swagger UI</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div id="swagger-ui"></div>

<script>
    window.onload = function () {
        window.ui = SwaggerUIBundle({
            urls: <?= Json::encode($urls); ?>,
            dom_id: '#swagger-ui',
            deepLinking: true,
            validatorUrl: null,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "StandaloneLayout"
        });
    }
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
