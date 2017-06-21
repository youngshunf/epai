<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

$this->title = "订单支付";
$this->registerJsFile('@web/js/PCASClass.js',['position'=> View::POS_HEAD]);
$hasAddress=empty($order->address)?0:1;
?>

    <div class="panel-white">
    <h5><?= Html::encode($this->title) ?></h5>
 				<p><label>订单状态:</label><span class="red"><?= CommonUtil::getDescByValue('order', 'status', $order->status)?></span></p>
                 <p><label>商品名称:</label><?=$order->goods_name?></p>
                 <p><label>金额:</label><span class="red">￥<?= $order->amount?></span></p>
                 <p><label>数量:</label><span class="green"><?= $order->number?></span></p>
                 <?php if(!empty($order->address)){?>
                 <p><label>收货地址:</label><span ><?= $order->address?></span></p>
                 <?php }?>
                
            <?php if($order->status==0){?>
             <p class="list-group-item" id="newAddress"><span class="glyphicon glyphicon-plus" style="color: rgb(255, 140, 60);"></span>新增收货地址</p>
             <div class="form-group center">
             <input type="hidden" name="order-guid" value="<?= $order->order_guid?>">              
                <button class="btn btn-success"  type="button"  onclick="callpay()"  >立即支付</button>
              </div> 
              <?php }?> 
              
              <?php if($order->status==2){?>
               <div class="form-group center">
                <a class="btn btn-danger"   href="<?= Url::to(['user/confirm-goods','id'=>$order->id])?>"  >确认收货</a>
              </div> 
              <?php }?>
          
  </div>
  
  <!-- 收货地址-->
<div class="modal fade" id="AddressModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               收货地址
            </h4>
         </div>
         <div class="modal-body">
         <?php if(yii::$app->user->isGuest){?>
            <div class="form-group center">
                <p>您还未登录,请先
                <a class="btn btn-success" href="<?= Url::to(['site/login'])?>">登录</a>
                <a class="btn btn-primary" href="<?= Url::to(['site/register'])?>">注册</a>
                </p>
            </div>
         <?php }else{ ?>
       
            <form action="<?= Url::to(['auction/new-order-address'])?>" method="post" onsubmit="return checkAddress()">
            	<input type="hidden" name="orderid" value=" <?= $order->id ?>">
            	<div class="form-group required" >
            	<label class="label-control">省:</label>
            	<select name="province" id="province" >    </select>
            	<label class="label-control">市:</label>
            	<select  name="city" id="city" >       </select>
            	<label class="label-control">区/县:</label>
            	<select  name="district" id="district" >       </select> 
            	</div>
            	<script type="text/javascript">
            	new PCAS("province","city","district");
            	</script>
            	<div class="form-group required" >
            	<label class="label-control">详细地址:</label>
            	<input type="text" name="address" id="address"  class="form-control">  
            	</div>
            	<div class="form-group required" >
            	<label class="label-control">收件人:</label>
            	<input type="text" name="name" id="name"  class="form-control">  
            	</div>
            	<div class="form-group required" >
            	<label class="label-control">联系电话:</label>
            	<input type="text" name="mobile" id="mobile"  value="<?= yii::$app->user->identity->mobile?>"  class="form-control">  
            	</div>
            	<div class="form-group required" >
            	<label class="label-control">收件单位(选填):</label>
            	<input type="text" name="company" id="company"  class="form-control">  
            	</div>
             <div class="form-group center">
            	<button type="submit" class="btn btn-success ">提交</button>
            	</div>
            	</form>
            	<?php }?>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default"  id="modal-close"
               data-dismiss="modal">关闭
            </button>
         
         </div>
      </div><!-- /.modal-content -->
</div>
</div><!-- /.modal -->

<script type="text/javascript">

var hasAddress=<?= $hasAddress?>;

$("#newAddress").click(function(){
    $("#AddressModal").modal("show");
});

function checkAddress(){
	if(!$('#province').val()){
	    modalMsg('请选择省份');
	    return false;
	}
	if(!$('#city').val()){
	    modalMsg('请选择城市');
	    return false;
	}
	if(!$('#district').val()){
	    modalMsg('请选择区县');
	    return false;
	}
	if(!$('#address').val()){
	    modalMsg('请填写地址');
	    return false;
	}
	if(!$('#name').val()){
	    modalMsg('请填写姓名');
	    return false;
	}
	if(!$('#mobile').val()){
	    modalMsg('请填写电话');
	    return false;
	}

	showWaiting('正在提交,请稍候...');
	return true;
}
//调用微信JS api 支付
function jsApiCall()
{
	WeixinJSBridge.invoke(
		'getBrandWCPayRequest',
		<?php echo empty($jsApiParameters)?'':$jsApiParameters; ?>,
		function(res){
			WeixinJSBridge.log(res.err_msg);
			if(res.err_msg == "get_brand_wcpay_request:ok" ){
				
				location.href="<?= Url::to(['site/pay-do','order_guid'=>$order->order_guid])?>";
			}else{
			
				location.href="<?= Url::to(['site/pay-result','order_guid'=>$order->order_guid])?>";
			}
			
		}
	);
}

function callpay()
{
	if(hasAddress==0){
      modalMsg('请先填写收货地址再支付!');
      return;
	}
	
	if (typeof WeixinJSBridge == "undefined"){
	    if( document.addEventListener ){
	        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
	    }else if (document.attachEvent){
	        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
	        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
	    }
	}else{
	    jsApiCall();
	}
}



</script>
