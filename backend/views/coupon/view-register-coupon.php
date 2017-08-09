<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $model common\models\Coupon */

$this->title = '注册优惠';
$this->params['breadcrumbs'][] = ['label' => '优惠券管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <p>
        <?= Html::a('修改', ['update-register-coupon', 'id' => $model->id], [
            'class' => 'btn btn-danger',
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            ['attribute'=>'is_open',
            'value'=>CommonUtil::getDescByValue('register_coupon', 'is_open', $model->is_open)
            ],
            'amount',
            'min_amount',
            'expire_day',
            'remark',
        ],
    ]) ?>

</div>
