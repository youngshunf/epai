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

    <p>
        <?= Html::a('发布拍品', ['create-goods','cateid'=>empty($cate)?"1":$cate->cateid], ['class' => 'btn btn-success']) ?>
    </p>

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
            ['attribute'=>'状态','value'=>function ($model){
                return CommonUtil::getDescByValue('auction_goods', 'status', $model->status);
            }],
            ['attribute'=>'sort','label'=>'排序'],
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
            	'template'=>'{view-goods}{update-goods}{delete-goods}{auction-deal}{auction-fail}',
	             'buttons'=>[
					'view-goods'=>function ($url,$model,$key){
	                     return  Html::a('查看 |', $url, ['title' => '查看分类'] );
					},
					'update-goods'=>function ($url,$model,$key){
					       return  Html::a('修改 | ', $url, ['title' => '修改分类'] );					       												   
				},
					'delete-goods'=>function ($url,$model,$key){
					return  Html::a('删除 | ', $url, ['title' => '删除分类', 'data-confirm'=>'是否确定删除该分类以及该分类下的所有资讯？'] );
					},
					'auction-deal'=>function ($url,$model,$key){
					if($model->status==1)
					 return  Html::a('成交 | ', $url, ['title' => '成交并下线', 'data-confirm'=>'您确定要把该产品标记为成交并立即下线？'] );
					},
					'auction-fail'=>function ($url,$model,$key){
					if($model->status==1)
					return  Html::a('流拍 ', $url, ['title' => '流派并下线', 'data-confirm'=>'您确定要把该产品标记为流拍并立即下线？'] );
					},
					
				]
           	],
        ],
    ]); ?>

