<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\widgets\ListView;
use yii\helpers\Url;
use common\models\LotteryRec;
use common\models\Order;
use common\models\Address;

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '一元夺宝', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/mui.min.css');
?>

<div class="panel-white">
    <h5><?= Html::encode($this->title) ?>
    <span class="badge badge-success pull-right"><?= CommonUtil::getDescByValue('lottery_goods', 'status', $model->status)?></span>
    </h5>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此项目吗?',
                'method' => 'post',
            ],
        ]) ?>
        <a class="btn btn-success" href="#record">参与记录</a>
        <a class="btn btn-info" href="#"  id="manual-lottery">设置中奖人</a>
        <button class="btn btn-warning" id="manual-rec">增加参与人次</button>
    </p>
    <div class="row">
    <div class="col-md-6">
   <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo?>" class="img-responsive">
  
     <h5>商品描述</h5>
  <?= $model->desc?>
  </div>
  <div class="col-md-6">
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'price',
            'count_lottery',
            'count_view',
            ['attribute'=>'end_time','value'=>CommonUtil::fomatTime($model->end_time)],
            ['attribute'=>'发布时间','value'=>CommonUtil::fomatTime($model->created_at)],
            ['attribute'=>'更新时间','value'=>CommonUtil::fomatTime($model->updated_at)],
            
 
        ],
    ]) ?>
    <?php $lotteryRec=LotteryRec::findOne(['goods_guid'=>$model->goods_guid,'is_award'=>1]);
    if(!empty($lotteryRec)){
    ?>
 <ul class="mui-table-view">
 	<li class="mui-table-view-cell ">	
 	<p class="red">中奖者</p>
 	</li>
				<li class="mui-table-view-cell mui-media">				
					<?php if(empty($lotteryRec->user->img_path)){?>
						<img class="mui-media-object mui-pull-left"  src="<?= yii::getAlias('@avatar')?>/unknown.jpg" >
						<?php }else{?>
						<img class="mui-media-object mui-pull-left"  src="<?= $lotteryRec->user->img_path?>" >
						<?php }?>
						<div class="mui-media-body">
							<p><a><?= !empty($lotteryRec->user->nick)?$lotteryRec->user->nick:$lotteryRec->user->mobile?></a> <span>(IP:<?= $lotteryRec->ip?>)</span></p>
							<p>幸运号码:<span class="red"><?= $lotteryRec->lottery_code?></span>
							<span class="pull-right"><?= CommonUtil::fomatTime($lotteryRec->created_at)?></span>
							</p>
						</div>
				
				</li>
				<li class="mui-table-view-cell ">		
				<?php $order=Order::findOne(['order_guid'=>$lotteryRec->order_guid]);
				    if(!empty($order->address)){
				?>
				<p>收货地址: <?= $order->address?></p>
				
				<?php }else{
				$address=Address::findOne(['user_guid'=>$lotteryRec->user_guid,'is_default'=>1]);
				if(!empty($address)){
				   $order->address_id=$address->id;
				    $order->address=$address['province'].' '.$address['city'].' '.$address['district'].' '.$address['address'].' '.$address['company'].' '.$address['name'].' '.$address['phone'];
				    $order->save();
				    ?>
				<p>收货地址: <?= $order->address?></p>
				<?php } }?>
				<?php if(!empty($order)){?>
			     	<p><a class="btn btn-success" href="<?= Url::to(['order/view','id'=>@$order->id])?>">去发货</a></p>
				<?php }?>
				</li>
    </ul>
    <?php }?>
    <div id="record">
    <h3>参与记录</h3>    
    <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_rec_item',            
           'layout'=>"{items}\n{pager}"
      ])?> 
    </div>
  </div>
</div>
</div>

       <!-- 模态框（Modal） -->
<div class="modal fade" id="lotteryModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               设置中奖者
            </h4>
         </div>
         <div class="modal-body">
            	<form action="<?= Url::to(['manual-lottery'])?>" method="post" onsubmit="return check()">
            	<input type="hidden" name="_csrf" value="<?= yii::$app->request->csrfToken?>">
            	<input type="hidden" name="goods_guid" value="<?= $model->goods_guid?>">
            	<div class="form-group">
            	<label class="label-control">中奖人手机号</label>
            	<input type="text" name="mobile" class="form-control" id="mobile">
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
		
		  <!-- 模态框（Modal） -->
<div class="modal fade" id="lotteryRecModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               增加参与人次
            </h4>
         </div>
         <div class="modal-body">
            	<form action="<?= Url::to(['manual-lottery-rec'])?>" method="post" onsubmit="return checkRec()">
            	<input type="hidden" name="_csrf" value="<?= yii::$app->request->csrfToken?>">
            	<input type="hidden" name="goods_guid" value="<?= $model->goods_guid?>">
            	<p class="red">剩余人次: <span id="leftTimes"><?= $model->price-$model->count_lottery ?></span></p>
            	<div class="form-group">
            	<label class="label-control">参与人次</label>
            	<input type="number" name="numbers" class="form-control" id="numbers">
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
$('#manual-lottery').click(function(){
    $('#lotteryModal').modal('show');

});
$('#manual-rec').click(function(){
	 $('#lotteryRecModal').modal('show');
});
      
function check(){
	if(!$('#mobile').val()){
	    modalMsg('请填写手机号');
	    return false;
	}

	showWaiting('正在提交,请稍候...');
	return true;
}

function checkRec(){
	if(!$('#numbers').val()){
	    modalMsg('请填写手机号');
	    return false;
	}
	if($('#numbers').val()>=parseInt($('#leftTimes').html())){
		modalMsg('参与人次不能大于剩余人次');
	    return false;
	}

	showWaiting('正在提交,请稍候...');
	return true;
}
 </script>