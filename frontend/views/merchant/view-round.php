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
      <!--  <?= Html::a('删除', ['delete-round', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此专场?',
                'method' => 'post',
            ],
        ]) ?>
         --> 
        	<?php if($model->auth_status==-1){
					echo   Html::a('提交审核', ['submit-auth','id'=>$model->id], ['title' => '提交审核','class'=>'btn btn-success', 'data-confirm'=>'您是否要提交该专场以及该专场的拍品进行审核？'] );
}?>
    </p>
    <div class="row">
  <div class="col-md-6">
   <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo?>" class="img-responsive">
  </div>
  <div class="col-md-6">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        ['attribute'=>'发布者','value'=>@$model->merchant->name],
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
 
      <?php if(yii::$app->user->identity->merchant_role==1&&$countGoods<yii::$app->params['normalMerchant.goods']){?>
    <?php if($model->auth_status==-1){?>
    <p>
        <?= Html::a('发布拍品', ['create-goods','cateid'=>empty($cate)?"1":$cate->cateid,'roundid'=>$model->id], ['class' => 'btn btn-success']) ?>
    </p>
    <p class="red">普通用户每个专场最多发布8个拍品,拍品发布完后请记得提交审核。专场提交审核后将不能再发布拍品！</p>
    <?php }?>
    <?php }
    if (yii::$app->user->identity->merchant_role==2&&$countGoods<yii::$app->params['seniorMerchant.goods']){?>
      <?php if($model->auth_status==-1){?>
    <p>
        <?= Html::a('发布拍品', ['create-goods','cateid'=>empty($cate)?"1":$cate->cateid,'roundid'=>$model->id], ['class' => 'btn btn-success']) ?>
    </p>
    <p class="red">高级用户每个专场最多发布10个拍品,拍品发布完后请记得提交审核。专场提交审核后将不能再发布拍品！</p>
    <?php }?>
    <?php }?>
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
            ['attribute'=>'审核状态','value'=>function ($model){
                return CommonUtil::getDescByValue('auction_goods', 'auth_status', $model->auth_status);
            }],
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
