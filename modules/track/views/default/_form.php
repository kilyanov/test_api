<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\track\models\Track;

/** @var yii\web\View $this */
/** @var Track $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="track-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'track_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(Track::getStatusList()) ?>
    <div class="form-group" style="margin-top: 50px;">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
