<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\GuaranteeFee;
use common\models\CommonUtil;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'VIP用户保证金退款';
$this->params['breadcrumbs'][] = $this->title;
?>

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'user.mobile',
            'user.nick',
            ['attribute'=>'订单编号',
            'value'=>function ($model){
            $order=Order::findOne(['biz_guid'=>$model->fee_guid,'type'=>Order::TYPE_GUARANTEE]);
              if(!empty($order)){
                  return $order->orderno;
              }else{
                  return '订单不存在';
              }
            }],
            ['attribute'=>'订单状态',
            'value'=>function ($model){
                $order=Order::findOne(['biz_guid'=>$model->fee_guid,'type'=>Order::TYPE_GUARANTEE]);
                if(!empty($order)){
                    return CommonUtil::getDescByValue('order', 'status', $order->status);
                }else{
                    return '订单不存在';
                }
            }
            ],
             'fee.guarantee_fee',
             
             ['attribute'=>'退款状态',
             'value'=>function ($model){
              return CommonUtil::getDescByValue('vip_refund', 'status', $model->status);
             },
             
             ],
            
                [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
             	'options'=>['width'=>'100px'],
            	'template'=>'{vip-refund-do}{vip-refund-cancel}',
	             'buttons'=>[
					'vip-refund-do'=>function ($url,$model,$key){
					if($model->status==0){
					    return  Html::a('退还保证金', $url, ['title' => '退还保证金','class'=>'btn btn-danger'] );
					}
	                    
					},
					'vip-refund-cancel'=>function ($url,$model,$key){
					if($model->status==0){
					    return  Html::a('取消退款', $url, ['title' => '取消退款','class'=>'btn btn-warning'] );
					}
					}
				
				]
           	],
        ],
    ]); ?>

