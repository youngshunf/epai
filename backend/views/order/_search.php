<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SearchOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_guid') ?>

    <?= $form->field($model, 'order_guid') ?>

    <?= $form->field($model, 'orderno') ?>

    <?= $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'biz_guid') ?>

    <?php // echo $form->field($model, 'goods_name') ?>

    <?php // echo $form->field($model, 'number') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'is_pay') ?>

    <?php // echo $form->field($model, 'pay_time') ?>

    <?php // echo $form->field($model, 'sent_time') ?>

    <?php // echo $form->field($model, 'confirm_time') ?>

    <?php // echo $form->field($model, 'cancel_time') ?>

    <?php // echo $form->field($model, 'express_number') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
