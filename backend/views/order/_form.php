<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_guid')->textInput(['maxlength' => 48]) ?>

    <?= $form->field($model, 'order_guid')->textInput(['maxlength' => 48]) ?>

    <?= $form->field($model, 'orderno')->textInput(['maxlength' => 48]) ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'biz_guid')->textInput(['maxlength' => 48]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'is_pay')->textInput() ?>

    <?= $form->field($model, 'pay_time')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'updated_at')->textInput(['maxlength' => 20]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
