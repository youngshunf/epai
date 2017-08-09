<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchNews */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title =$cate->name;
$this->params['breadcrumbs'][] = ['label' => '资讯分类', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新闻发布', ['create','id'=>$cate->cateid], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],

        
            'title',
           ['attribute'=>'内容','value'=>function ($model){
               return CommonUtil::cutHtml($model->content, 50);
           }],
           ['attribute'=>'发布时间','value'=>function ($model){
               return CommonUtil::fomatTime($model->created_at);
           }],
         
              [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{view}{update}{delete}{recommend}',
	             'buttons'=>[
					'view'=>function ($url,$model,$key){
	                     return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看资讯'] );
					},
					'update'=>function ($url,$model,$key){
					
					       return  Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '修改资讯'] );					       												   
				},
					'delete'=>function ($url,$model,$key){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除资讯', 'data-confirm'=>'是否确定删除该资讯？'] );
					},
					
					'recommend'=>function ($url,$model,$key){
					if($model->is_recommend==0)
					return  Html::a('<span class="glyphicon glyphicon-arrow-up"></span>', $url, ['title' => '推荐资讯', 'data-confirm'=>'是否确定推荐该资讯？'] );					
					if($model->is_recommend==1)
					    return  Html::a('<span class="glyphicon glyphicon-arrow-down"></span>', $url, ['title' => '取消推荐', 'data-confirm'=>'是否确定取消推荐？'] );
						
					},
					
				]
           	],
        ],
    ]); ?>


