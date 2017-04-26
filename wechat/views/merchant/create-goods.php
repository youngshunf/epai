<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

$this->title = '发布拍品';
$this->params['breadcrumbs'][] = ['label' => '拍品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auction-goods-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_goods_form', [
        'model' => $model,
        'cate'=>$cate,
        'round'=>$round
    ]) ?>

</div>
