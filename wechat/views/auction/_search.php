<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SearchAuctionGoods */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auction-goods-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'goods_guid') ?>

    <?= $form->field($model, 'cateid') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'desc') ?>

    <?php // echo $form->field($model, 'start_price') ?>

    <?php // echo $form->field($model, 'delta_price') ?>

    <?php // echo $form->field($model, 'lowest_deal_price') ?>

    <?php // echo $form->field($model, 'current_price') ?>

    <?php // echo $form->field($model, 'count_auction') ?>

    <?php // echo $form->field($model, 'count_view') ?>

    <?php // echo $form->field($model, 'count_collection') ?>

    <?php // echo $form->field($model, 'deal_price') ?>

    <?php // echo $form->field($model, 'deal_user') ?>

    <?php // echo $form->field($model, 'start_time') ?>

    <?php // echo $form->field($model, 'end_time') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
