<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

$this->title = "订单支付";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">

  <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>

    <div class="panel-white">
    <h5><?= Html::encode($this->title) ?></h5>

                 <p><label>商品名称:</label><?=$order->goods_name?></p>
                 <p><label>金额:</label><span class="red">￥<?= $order->amount?></span></p>
                 <p><label>数量:</label><span class="green"><?= $order->number?></span></p>
             <div class="form-group center">
         
                <p>请使用微信扫码下面的二维码进行支付</p>
               <img src="<?= yii::getAlias('@photo').'/qrcode/'.$order->order_guid.'.png'?>"  class="img-responsive" />
                <p class="center">微信支付</p>
      
              </div>  
            
          
  </div>
</div>
<script type="text/javascript">

function checkOrder(){
	$.ajax({
	    url:"<?= Url::to(['check-order'])?>",
	    type:"post",
	    data:{
	        order_guid:"<?=$order->order_guid?>"
	    },
	    success:function(rs){
	        if(rs=="paid"){
	            location.href="<?= Url::to(['site/pay-result','order_guid'=>$order->order_guid])?>";
	        }
	    },
	    error:function(e){

	    }

	});
}

$(function(){
	   setInterval(checkOrder,2000);
});

</script>
