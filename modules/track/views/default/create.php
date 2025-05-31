<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\track\models\Track $model */

$this->title = 'Создать запись';
$this->params['breadcrumbs'][] = ['label' => 'Трекеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="track-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>