<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SearchAuctionGoods */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auction-goods-search">

    <?php $form = ActiveForm::begin([
        'action' => ['goods'],
        'method' => 'get',
    ]); ?>
    

    <?php  echo $form->field($model, 'status')->dropDownList(['1'=>'正在拍卖','2'=>'未开始','3'=>'已结束'])->label('状态') ?>

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
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
