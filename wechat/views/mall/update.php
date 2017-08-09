<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LotteryGoods */

$this->title = '修改商品: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'E拍宝商城', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
