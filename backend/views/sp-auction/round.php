<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchAuctionGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '拍卖专场';
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
            ['attribute'=>'merchant.name',
            'label'=>'发布者'
                ],
            ['attribute'=>'merchant.role',
            'label'=>'卖家等级',
             'value'=>function ($model){
                 return CommonUtil::getDescByValue('user', 'merchant_role', $model->merchant->merchant_role);
             }
             ],
            'name',
            'sort',
            ['attribute'=>'状态','value'=>function ($model){
                return CommonUtil::getDescByValue('auction_round', 'auth_status', $model->auth_status);
            }],
           ['attribute'=>'描述','value'=>function ($model){
               return CommonUtil::cutHtml($model->desc, 50);
           }],
           ['attribute'=>'start_time','value'=>function ($model){
               return CommonUtil::fomatTime($model->start_time);
           }],
           ['attribute'=>'end_time','value'=>function ($model){
               return CommonUtil::fomatTime($model->end_time);
           }],
           ['attribute'=>'发布时间','value'=>function ($model){
               return CommonUtil::fomatTime($model->created_at);
           }],
            
                [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{view-round}{update-round}{pass-round}{deny-round}',
	             'buttons'=>[
					'view-round'=>function ($url,$model,$key){
	                     return  Html::a('查看 | ', $url, ['title' => '查看专场'] );
					},
					'update-round'=>function ($url,$model,$key){
					
					       return  Html::a('修改 | ', $url, ['title' => '修改分类'] );					       												   
				},
					'pass-round'=>function ($url,$model,$key){
					if($model->auth_status!=1)
					return  Html::a('审核通过 | ', $url, ['title' => '删除分类', 'data-confirm'=>'您是否要审核通过此专场？'] );
					},
					'deny-round'=>function ($url,$model,$key){
					if($model->auth_status!=2)
					    return  Html::a('审核不通过', $url, ['title' => '删除分类', 'data-confirm'=>'您是否要将此专场设置为不通过？'] );
					},
					
				]
           	],
        ],
    ]); ?>

