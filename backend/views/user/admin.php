<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员管理';
$this->params['breadcrumbs'][] = $this->title;
?>
    <h3><?= Html::encode($this->title) ?></h3>
    <?php if(yii::$app->user->identity->role_id==99){?>
  <p><a class="btn btn-success"  href="<?= Url::to(['user/create-admin'])?>">添加管理员</a></p>
    <?php }?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'username',
            'name',
             'nick',
           ['attribute'=>'role_id',
           'label'=>'角色',
           'value'=>function ($model){
              return CommonUtil::getDescByValue('admin_user', 'role_id', $model->role_id);
           }
           ],
                [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{view-admin}{update-admin}',
	             'buttons'=>[
					'view-admin'=>function ($url,$model,$key){
	                     return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看详细'] );
					},
					'update-admin'=>function ($url,$model,$key){
					 if(yii::$app->user->identity->role_id==99 || $model->user_guid==yii::$app->user->identity->user_guid){
					       return  Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '修改用户'] );					       												   
					 }
					 },
			/* 		'delete-admin'=>function ($url,$model,$key){
					if($model->role_id!=99){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除用户', 'data-confirm'=>'是否确定删除该用户？'] );
				        	}
					   }, */
					
				]
           	],
        ],
    ]); ?>

