<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchCoupon */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '优惠券管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('发放优惠券', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'coupon_code',
            'amount',
            ['attribute'=>'status',
              'filter'=>['1'=>'未使用','2'=>'已使用','99'=>'已过期'],
            'value'=>function ($model){
                return CommonUtil::getDescByValue('coupon', 'status', $model->status);
            }
            ],
//             ['attribute'=>'type',
//                 'filter'=>['1'=>'注册优惠','2'=>'系统发放'],
//             'value'=>function ($model){
//             return CommonUtil::getDescByValue('coupon', 'type', $model->type);
//             }
//             ],
             ['attribute'=>'end_time',
                'format'=>['date','php:Y-m-d H:i:s']
             ],
             'user.name',
             'user.mobile',
            // 'type',
            // 'min_amout',
            // 'created_user',
            // 'user_guid',
            // 'remark',
            ['attribute'=>'created_at',
                'format'=>['date','php:Y-m-d H:i:s']
             ],

            ['class' => 'yii\grid\ActionColumn','header'=>'操作',
                'template'=>'{view}{delete}'
            ],
        ],
    ]); ?>

</div>
