<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\Address;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '一元夺宝', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/mui.min.js');
$this->registerJsFile('@web/js/PCASClass.js',['position'=> View::POS_HEAD]);
?>
<style>
a{
	color:#000;
}
.mui-table-view .mui-media-object {
  line-height: 42px;
   max-width: 70px; 
   height: 70px; 
}
</style>
   <ul class="mui-table-view">
				<li class="mui-table-view-cell mui-media">
					<a href="javascript:;">
						<img class="mui-media-object mui-pull-left" src="<?= yii::getAlias('@photo').'/'.$model->path.'thumb/'.$model->photo?>">
						<div class="mui-media-body">				
							<p class='mui-ellipsis bold'   ><?= $model->name?></p>
							 <p>
                        <span>总需 <i class="green"><?= $model->price?></i> </span>/
                          <span>剩余 <i class="red-normal"><?= $model->price-$model->count_lottery?></i></span>
                        </p>
                        <form action="<?= Url::to(['submit-order'])?>" method="post" id="order-form" onsubmit="return check()">
                        <input type="hidden" name="goods-guid"  value="<?= $model->goods_guid?>">
                        <div class="mui-numbox"  data-numbox-min='1'  data-numbox-max='<?= $model->price-$model->count_lottery?>'>
        					<button class="mui-btn mui-numbox-btn-minus" type="button">-</button>
        					<input class="mui-numbox-input" type="number" value="1"  id="number" name="number"/>
        					<button class="mui-btn mui-numbox-btn-plus" type="button">+</button>
        				</div>
						</div>
						</form>
					</a>
				</li>
				<li class="mui-table-view-cell ">
				 <ul class="mui-table-view">
                            <?php $address=Address::findOne(['user_guid'=>yii::$app->user->identity->user_guid,'is_default'=>1]);
                                if(!empty($address)){?>
                           <li class="mui-table-view-cell ">收货地址:
                           <?= $address->province?>   <?= $address->city?>   <?= $address->district?>   <?= $address->address?>   <?= $address->company?>   <?= $address->name?>   <?= $address->phone?>
                            
                           </li>
                           <?php }?>
                           <li class="mui-table-view-cell " id="newAddress"><span class="glyphicon glyphicon-plus" style="color: rgb(255, 140, 60);"></span>新增收货地址</li>
                           </ul>
				</li>
	</ul>

<div class="bottom-button">
<p> 参与 <span id="total-p" class="green bold">1</span> 人次 ,需支付人民币<span class="red-normal bold" id="total-m"> ￥ 1</span></p>
<button class="btn btn-danger btn-block" id="submit">提交订单</button>
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
               新增收货地址
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
       
            <form action="<?= Url::to(['auction/new-address'])?>" method="post" onsubmit="return checkAddress()">
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
mui.init();
$("#number").change(function(){
    var num=$(this).val();
$("#total-p").html(num);
$("#total-m").html("￥"+num);
    
});

$("#number").blur(function(){
    var num=$(this).val();
$("#total-p").html(num);
$("#total-m").html("￥"+num);
    
});

$('#newAddress').click(function(){
 $('#AddressModal').modal('show');
});


var max=parseInt(<?= $model->price-$model->count_lottery?>);
function check(){	
	var num=$("#number").val();
	if(num>max){
	    modalMsg("购买不能超过剩余份数");
	    return false;
	}else if(num==0){
		modalMsg("请至少购买1份");
	    return false;
	}

	showWaiting("正在提交,请稍候...");
	return true;
}

$('#submit').click(function(){
    $("#order-form").submit();
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



</script>
