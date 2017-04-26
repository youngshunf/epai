<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchAuctionGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '拍品管理:'.empty($cate)?"全部拍品":$cate->name;
$this->params['breadcrumbs'][] = $this->title;
?>

    <h3><?= Html::encode($this->title) ?></h3>
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
            'name',
            ['attribute'=>'merchant.name',
            'label'=>'发布者'
                ],
            ['attribute'=>'merchant.role',
                'label'=>'卖家等级',
                'value'=>function ($model){
                return CommonUtil::getDescByValue('user', 'merchant_role', $model->merchant->merchant_role);
                }
             ],
             'start_price',
            'delta_price',
             'lowest_deal_price',
             'current_price',
            // 'count_auction',
            // 'count_view',
            // 'count_collection',
            // 'deal_price',
            // 'deal_user',
            // 'start_time',
            // 'end_time',
            ['attribute'=>'auth_status',
            'label'=>'审核状态',
            'options'=>['width'=>'150px'],
            'filter'=>['0'=>'待审核','1'=>'审核通过','2'=>'审核未通过'],
            'value'=>function ($model){
                return CommonUtil::getDescByValue('auction_goods', 'auth_status', $model->auth_status);
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
            	'template'=>'{view-goods}{update-goods}{delete-goods}{pass-goods}{deny-goods}',
	             'buttons'=>[
					'view-goods'=>function ($url,$model,$key){
	                     return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看拍品'] );
					},
					'update-goods'=>function ($url,$model,$key){
					
					       return  Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '修改拍品'] );					       												   
				},
					'delete-goods'=>function ($url,$model,$key){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除拍品', 'data-confirm'=>'您是否确定要删除该拍品,删除后将不可恢复？'] );
					},
					'pass-goods'=>function ($url,$model,$key){
					if($model->auth_status!=1)
					return  Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, ['title' => '审核通过', 'data-confirm'=>'您确定要审核通过该拍品吗？'] );
					},
					'deny-goods'=>function ($url,$model,$key){
					if($model->auth_status!=2)
					return  Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, ['title' => '审核不通过', 'data-confirm'=>'您确定要审核不通过该拍品吗？'] );
					},
					
				]
           	],
        ],
    ]); ?>

