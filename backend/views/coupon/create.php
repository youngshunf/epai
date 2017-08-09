<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Coupon */

$this->title = '新增优惠券';
$this->params['breadcrumbs'][] = ['label' => '优惠券管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
