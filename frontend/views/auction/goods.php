<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchAuctionGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '拍品管理:'.$cate->name;
$this->params['breadcrumbs'][] = $this->title;
?>

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('发布拍品', ['create-goods','id'=>$cate->cateid], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
         
            'name',
               ['attribute'=>'desc','value'=>function ($model){
               return CommonUtil::cutHtml($model->desc, 50);
           }],
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
              ['attribute'=>'时间','value'=>function ($model){
               return CommonUtil::fomatTime($model->created_at);
           }],
            
                [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{view-goods}{update-goods}{delete-goods}',
	             'buttons'=>[
					'view-goods'=>function ($url,$model,$key){
	                     return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看分类'] );
					},
					'update-goods'=>function ($url,$model,$key){
					
					       return  Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '修改分类'] );					       												   
				},
					'delete-goods'=>function ($url,$model,$key){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除分类', 'data-confirm'=>'是否确定删除该分类以及该分类下的所有资讯？'] );
					},
					
				]
           	],
        ],
    ]); ?>

