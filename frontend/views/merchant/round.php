<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchAuctionGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '专场管理';
$this->params['breadcrumbs'][] = $this->title;
?>

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php if(yii::$app->user->identity->merchant_role==1&&$countRound<yii::$app->params['normalMerchant.round']){?>
    <p>
        <?= Html::a('发布专场', ['create-round'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php }
    if (yii::$app->user->identity->merchant_role==2&&$countRound<yii::$app->params['seniorMerchant.round']){?>
    <p>
        <?= Html::a('发布专场', ['create-round'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php }?>

     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],        
            'name',
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
            	'template'=>'{view-round}{update-round}{submit-auth}',
	             'buttons'=>[
					'view-round'=>function ($url,$model,$key){
	                     return  Html::a('查看 | ', $url, ['title' => '查看专场'] );
					},
					'update-round'=>function ($url,$model,$key){
					
					       return  Html::a('修改 | ', $url, ['title' => '修改专场'] );					       												   
				},
					'submit-auth'=>function ($url,$model,$key){
					if($model->auth_status==-1)
					return  Html::a('提交审核', $url, ['title' => '提交审核', 'data-confirm'=>'您是否要提交该专场以及该专场的拍品进行审核？'] );
					},
					
				]
           	],
        ],
    ]); ?>

