<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchOrder */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '订单管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'user.mobile',
            'orderno',
           ['attribute'=>'type',
           'filter'=>['0'=>'保证金订单','1'=>'拍卖订单','2'=>'商城订单','3'=>'夺宝订单'],
           'value'=>function ($model){
              return CommonUtil::getDescByValue('order', 'type', $model->type); 
           },
           'options'=>['width'=>'150px'],
           ],
           'goods_name',
             'number',
            'amount',
            ['attribute'=>'status',
                'filter'=>['0'=>'待付款','1'=>'待发货','2'=>'待确认收货','3'=>'已收货','98'=>'已退款' ,'99'=>'已取消'],
                'options'=>['width'=>'150px'],
                'value'=>function ($model){
              return CommonUtil::getDescByValue('order', 'status', $model->status); 
           }],
            [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
             	'options'=>['width'=>'200px'],
            	'template'=>'{view}{update}{delete}{cancel}',
	             'buttons'=>[
					'view'=>function ($url,$model,$key){
	                     return  Html::a('查看 | ', $url, ['title' => '查看详细'] );
					},
					'update'=>function ($url,$model,$key){
					return  Html::a('修改 | ', $url, ['title' => '查看详细'] );
					},
					'delete'=>function ($url,$model,$key){
					return  Html::a('删除 |', $url, ['title' => '删除订单', 'data' => [
                                        'confirm' => '您确定要删除此订单吗?',
                                        'method' => 'post',
                                    ],] );
					},
					'cancel'=>function ($url,$model,$key){
					return  Html::a('取消', $url, ['title' => '取消订单', 'data' => [
					    'confirm' => '您确定要取消此订单吗?',
					    'method' => 'post',
					],] );
					},
				]
           	],
        ],
    ]); ?>

</div>
