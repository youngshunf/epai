<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $model common\models\Coupon */

$this->title = $model->coupon_code;
$this->params['breadcrumbs'][] = ['label' => '优惠券管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <p>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除此优惠券吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'coupon_code',
            'amount',
            ['attribute'=>'status',
            'value'=>CommonUtil::getDescByValue('coupon', 'status', $model->status)
            ],
             ['attribute'=>'end_time',
                'format'=>['date','php:Y-m-d H:i:s']
             ],
            ['attribute'=>'type',
            'value'=>CommonUtil::getDescByValue('coupon', 'type', $model->type)
            ],
            'min_amount',
            'remark',
            ['attribute'=>'created_at',
                'format'=>['date','php:Y-m-d H:i:s']
             ],
            ['attribute'=>'updated_at',
            'format'=>['date','php:Y-m-d H:i:s']
            ],
        ],
    ]) ?>

</div>
