<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户管理';
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
            'mobile',
            'nick',
             'province',
             'city',
            
                [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
             	'options'=>['width'=>'200px'],
            	'template'=>'{view}{update}{delete}{reset-password}{ban-user}',
	             'buttons'=>[
					'view'=>function ($url,$model,$key){
	                     return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看详细'] );
					},
					'update'=>function ($url,$model,$key){
					
					       return  Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '修改用户'] );					       												   
				},
					'delete'=>function ($url,$model,$key){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除用户', 'data' => [
                                        'confirm' => '您确定要删除此用户?',
                                        'method' => 'post',
                                    ],] );
					},
					'reset-password'=>function ($url,$model,$key){
					return  Html::a(' 重置密码 | ', $url, ['title' => '重置密码', 'data' => [
					    'confirm' => '您确定重置此用户的密码吗?',
					    'method' => 'post',
					],] );
					},
					'ban-user'=>function ($url,$model,$key){
					if($model->status==1){
					    return  Html::a(' 禁止拍卖', $url, ['title' => '禁止拍卖',] );
					}elseif($model->status==0){
					    return  Html::a(' 允许拍卖', $url, ['title' => '允许拍卖',] );
					}
					
					},
				]
           	],
        ],
    ]); ?>

