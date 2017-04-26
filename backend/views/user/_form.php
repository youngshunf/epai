<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => 32]) ?>
    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'nick')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'province')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'country')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>

    

    <?= $form->field($model, 'district')->textInput(['maxlength' => 128]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
