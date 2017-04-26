<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '拍品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('修改', ['update-goods', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete-goods', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此项目吗?',
                'method' => 'post',
            ],
        ]) ?>
        
    <div class="row">
    <div class="col-md-6">
   <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo?>" class="img-responsive">
  </div>
  <div class="col-md-6">
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cateid',
            'name',
            'start_price',
            'delta_price',
            'lowest_deal_price',
            'current_price',
            'count_auction',
            'count_view',
            'count_collection',
            'deal_price',
            'deal_user',
            ['attribute'=>'start_time','value'=>CommonUtil::fomatTime($model->start_time)],
            ['attribute'=>'end_time','value'=>CommonUtil::fomatTime($model->end_time)],
            ['attribute'=>'发布时间','value'=>CommonUtil::fomatTime($model->created_at)],
            ['attribute'=>'更新时间','value'=>CommonUtil::fomatTime($model->updated_at)],
            
 
        ],
    ]) ?>
    </div>
     <div class="col-lg-12">
   <h5>商品描述</h5>
  <?= $model->desc?>
  </div>
</div>
<!-- 模态框（Modal） -->
<div class="modal fade" id="countModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               设置围观数
            </h4>
         </div>
         <div class="modal-body">
            	<form action="<?= Url::to(['manual-count'])?>" method="post" onsubmit="return check()">
            	<input type="hidden" name="_csrf" value="<?= yii::$app->request->csrfToken?>">
            	<input type="hidden" name="goods_guid" value="<?= $model->goods_guid?>">
            	<div class="form-group">
            	<label class="label-control">设置围观数量</label>
            	<input type="number" name="count" class="form-control"  id="count">
            	</div>
            	<div class="center">
            	<button type="submit"  class="btn btn-success">提交</button>
            	</div>
            	</form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default"  id="modal-close"
               data-dismiss="modal">关闭
            </button>
         
         </div>
      </div><!-- /.modal-content -->
</div>
		</div><!-- /.modal -->
		
		<script>
$('#manual-count').click(function(){
    $('#countModal').modal('show');

});
      
function check(){
	if(!$('#count').val()){
	    modalMsg('请填写围观数量');
	    return false;
	}

	showWaiting('正在提交,请稍候...');
	return true;
}
 </script>