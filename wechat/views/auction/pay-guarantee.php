<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

$this->title = "支付保证金";
$this->params['breadcrumbs'][] = ['label' => '天天易拍', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
          <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>

    <div class="panel-white">
    <h5><?= Html::encode($this->title) ?></h5>

    <div class="row">
     <div class="col-lg-12">
     <div class="panel-white">
                 <p><label>商品名称:</label><?= CommonUtil::getDescByValue('user', 'role_id', $guaranteeFee->user_role)?>竞拍保证金</p>
                 <p><label>金额:</label><span class="red">￥<?= $order->amount?></span></p>
             <div class="form-group center">
         
             <input type="hidden"  name="order-guid"  value="<?= $order->order_guid?>">
                
               <button class="btn btn-success"  type="button"  onclick="callpay()" >立即支付</button>
       
              </div>  
          
          
  </div>
  </div>
</div>
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
				
				location.href="<?= Url::to(['pay-guarantee','order_guid'=>$order->order_guid])?>";
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

