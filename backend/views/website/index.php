<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '首页轮播大图';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="home-photo-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('添加图片', ['create-photo'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'title',
            'url:url',

           ['attribute'=>'created_at',
           'label'=>'时间',
           'format'=>['date', 'php:Y-m-d H:i:s']
           ],
       

               [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{view-photo}{update-photo}{delete-photo}',
	             'buttons'=>[
					'view-photo'=>function ($url,$model,$key){
	                     return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看图片'] );
					},
					'update-photo'=>function ($url,$model,$key){
					
					       return  Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '修改图片'] );					       												   
				},
					'delete-photo'=>function ($url,$model,$key){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除图片', 'data-confirm'=>'是否确定删除该图片？'] );
					},
					
				]
           	],
        ],
    ]); ?>

</div>
