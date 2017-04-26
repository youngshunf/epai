<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '拍品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('修改', ['update-round', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete-round', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此专场?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="row">
  <div class="col-md-6">
   <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo?>" class="img-responsive">
  </div>
  <div class="col-md-6">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        ['attribute'=>'创建者','value'=>$model->user->name],
            'name',
            'desc',
            'source',
            ['attribute'=>'start_time','value'=>CommonUtil::fomatTime($model->start_time)],
            ['attribute'=>'end_time','value'=>CommonUtil::fomatTime($model->end_time)],
           ['attribute'=>'创建时间','value'=>CommonUtil::fomatTime($model->created_at)],
            ['attribute'=>'更新时间','value'=>CommonUtil::fomatTime($model->updated_at)],
        ],
    ]) ?>
 </div>
 
  <div class="col-md-12">
 <h5>专场拍品</h5>
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

</div>
</div>
