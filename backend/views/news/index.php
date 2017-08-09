<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchNews */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '资讯分类';
$this->params['breadcrumbs'][] = $this->title;
?>


    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新增分类', ['create-cate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],        
            'name',
           ['attribute'=>'描述','value'=>function ($model){
               return CommonUtil::cutHtml($model->desc, 50);
           }],
           ['attribute'=>'创建时间','value'=>function ($model){
               return CommonUtil::fomatTime($model->created_at);
           }],
            
                [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{view-cate}{update-cate}',
	             'buttons'=>[
					'view-cate'=>function ($url,$model,$key){
	                     return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看分类'] );
					},
					'update-cate'=>function ($url,$model,$key){
					
					       return  Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '修改分类'] );					       												   
				},
					'delete-cate'=>function ($url,$model,$key){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除分类', 'data-confirm'=>'是否确定删除该分类以及该分类下的所有资讯？'] );
					},
					
				]
           	],
        ],
    ]); ?>


