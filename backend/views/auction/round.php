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

    <p>
        <?= Html::a('增加专场', ['create-round'], ['class' => 'btn btn-success']) ?>
    </p>

     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],        
            'name',
            'sort',
           ['attribute'=>'描述','value'=>function ($model){
               return CommonUtil::cutHtml($model->desc, 50);
           }],
           ['attribute'=>'start_time','value'=>function ($model){
               return CommonUtil::fomatTime($model->start_time);
           }],
           ['attribute'=>'end_time','value'=>function ($model){
               return CommonUtil::fomatTime($model->end_time);
           }],
           ['attribute'=>'创建时间','value'=>function ($model){
               return CommonUtil::fomatTime($model->created_at);
           }],
            
                [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{view-round}{update-round}{offline}',
	             'buttons'=>[
					'view-round'=>function ($url,$model,$key){
	                     return  Html::a('查看 | ', $url, ['title' => '查看分类'] );
					},
					'update-round'=>function ($url,$model,$key){
					
					       return  Html::a('修改 | ', $url, ['title' => '修改分类'] );					       												   
				},
					'delete-round'=>function ($url,$model,$key){
					return  Html::a('删除 |', $url, ['title' => '删除分类', 'data-confirm'=>'是否确定删除该分类以及该分类下的所有资讯？'] );
					},
					'offline'=>function ($url,$model,$key){
					if($model->offline==0){
					    return  Html::a('下架', $url, ['title' => '下架'] );
					}elseif($model->offline==1){
					    return  Html::a('上架', $url, ['title' => '上架']);
					}
						
					},
				]
           	],
        ],
    ]); ?>

