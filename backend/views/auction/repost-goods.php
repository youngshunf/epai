<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

$this->title = '重新发布: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '拍品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="auction-goods-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'cate'=>$cate,
        'round'=>$round
    ]) ?>

</div>
