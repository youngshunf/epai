<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\LotteryGoods */

$this->title = '发布宝贝';
$this->params['breadcrumbs'][] = ['label' => '一元夺宝', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
