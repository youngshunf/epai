<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchAuctionGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $goods->name.'--已提交保证金用户';
$this->params['breadcrumbs'][] = $this->title;
?>

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   <!--   <p><a class="btn btn-danger pull-right" href="<?= Url::to(['order/batch-refund-guarantee','goods_guid'=>$goods->goods_guid])?>">批量退还未成交用户保证金</a></p>-->
    <p class="clear"></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
        'firstPageLabel'=>'首页',
        'lastPageLabel'=>'尾页'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
             'user.mobile',
            'guarantee_fee',
            ['attribute'=>'status',
            'label'=>'状态',
            'value'=>function ($model){
            return CommonUtil::getDescByValue('guarantee_fee', 'status', $model->status);
            }
            ],
            ['attribute'=>'created_at',
                'label'=>'下单时间',
                'value'=>function ($model){
                return CommonUtil::fomatTime($model->created_at);
            }],
              ['attribute'=>'支付时间','value'=>function ($model){
               return CommonUtil::fomatTime($model->updated_at);
           }],
            
                [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
            	'template'=>'{refund}',
	             'buttons'=>[
					'refund'=>function ($url,$model,$key){
					if($model->status==1&&$model->is_deal==0){
	                     return  Html::a('退还保证金', ['order/guarantee-refund','id'=>$model->id], ['title' => '退还保证金','class'=>'btn btn-danger'] );
					   }
					},
				]
           	],
        ],
    ]); ?>

