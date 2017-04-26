<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->orderno;
$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <p class="center"> 
     <?php if($model->status==1){?>
       <button class="btn btn-success" id="sendGoods">发货</button>
     <?php }?>
     
      <?php if($model->status==2){?>
       <a class="btn btn-info"  href="http://www.kuaidi100.com/chaxun?com=<?= $model->express_company?>&nu=<?= $model->express_number?>"  target="_blank">快递查询</a>
     <?php }?>
    <?php if($model->status!=99){?>
    <?= Html::a('取消订单', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定取消此订单?',
                'method' => 'post',
            ],
        ]) ?>
        <?php }?>
        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'orderno',
            ['attribute'=>'type',
            'value'=>CommonUtil::getDescByValue('order', 'type', $model->type),
            ] ,
            'goods_name',
            'number',
            'amount',
              ['attribute'=>'status',
               'value'=>CommonUtil::getDescByValue('order', 'status', $model->status),
           ] ,
            ['attribute'=>'is_pay',
            'value'=>CommonUtil::getDescByValue('order', 'is_pay', $model->is_pay),
            ] ,
            
           ['attribute'=>'pay_time',
           'format'=>['date','php:Y-m-d H:i:s'],
           ] ,
           'address',
           ['attribute'=>'sent_time',
           'label'=>'发货时间',
           'format'=>['date','php:Y-m-d H:i:s'],
           ] ,
           ['attribute'=>'confirm_time',
           'label'=>'确认收货时间',
           'format'=>['date','php:Y-m-d H:i:s'],
           ] ,
          //  'cancel_time',
            'express_company',
            'express_number',
          
            ['attribute'=>'created_at',
           'format'=>['date','php:Y-m-d H:i:s'],
           ] ,
             ['attribute'=>'updated_at',
           'format'=>['date','php:Y-m-d H:i:s'],
           ] ,
        ],
    ]) ?>

</div>

<!-- 发货-->
<div class="modal fade" id="sendModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
              发货
            </h4>
         </div>
         <div class="modal-body">
            	
              <form method="post" action="<?php echo Url::to('send-goods')?>" onsubmit="return check()">						
        			<input type="hidden" value="<?= yii::$app->request->csrfToken?>" name="_csrf">	
        			<input type="hidden" value="<?= $model->order_guid?>" name="order_guid">	
        			<br>
        			<div class="form-group">
        			<label class="label-control">快递公司</label>
        			<input name="company" class="form-control" id="company">
        			</div>
        			<div class="form-group">
        			<label class="label-control">快递单号</label>
        			<input name="number" class="form-control" id="number">
        			</div>
        			
        			<input type="submit" value="确认发货"  class="btn btn-primary" >
        			
        		  <p id="errorImport1"></p>		
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
$('#sendGoods').click(function(){
	$('#sendModal').modal('show');
 });
function check(){
	if(!$('#company').val()){
		modalMsg('请输入快递公司!');
		return false;
	}
	if(!$('#number').val()){
		modalMsg('请输入快递单号!');
		return false;
	}
		showWaiting('正在导入,请稍候...');
		return true;
	
}
 </script>