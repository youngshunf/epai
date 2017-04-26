<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

$this->title = "订单支付";
?>

    <div class="panel-white">
    <h5><?= Html::encode($this->title) ?></h5>

                 <p><label>商品名称:</label><?=$order->goods_name?></p>
                 <p><label>金额:</label><span class="red">￥<?= $order->amount?></span></p>
                 <p><label>数量:</label><span class="green"><?= $order->number?></span></p>
             <div class="form-group center">
         
             <input type="hidden" name="order-guid" value="<?= $order->order_guid?>">              
                <button class="btn btn-success"  type="button"  onclick="callpay()"  >立即支付</button>
      
              </div>  
          
  </div>

<script type="text/javascript">


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
