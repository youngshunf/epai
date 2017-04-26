<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\Address;
use yii\web\View;
use common\models\Coupon;

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

$this->title = $model->name;
$this->registerJsFile('@web/js/mui.min.js');
$this->registerJsFile('@web/js/PCASClass.js',['position'=> View::POS_HEAD]);
?>
<style>
a{
	color:#000;
}
</style>
    <div class="col-md-6">
   <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo?>" class="img-responsive">
  </div>


    <div class="row">

  <div class="col-md-6">
    <div class="panel-white">
    <p class="bold"><?= $model->name?></p>
    <p>
      <span class="">价格: <i class="red-normal">￥<?= $model->price?></i> </span>
    </p>
    <p>
      <span class="">库存: <i ><?= $model->number?></i>  件</span>
    </p>
    <p>
      <span class="">已卖出: <i class="green"><?= $model->count_sales?></i>  件</span>
    </p>
    <p>
      <span class="">剩余: <?= $model->number-$model->count_sales?> 件 </span>
    </p>
    
         <form action="<?= Url::to(['submit-order'])?>" method="post" id="order-form" onsubmit="return check()">
            <input type="hidden" name="goods-guid"  value="<?= $model->goods_guid?>">
            <div class="mui-numbox " data-numbox-min='1' data-numbox-max='<?= $model->number-$model->count_sales?>'>
				<button class="mui-btn mui-numbox-btn-minus" type="button">-</button>
				<input class="mui-numbox-input" type="number" value="1"  id="number" name="number"/>
				<button class="mui-btn mui-numbox-btn-plus" type="button">+</button>
			</div>
			</div>
			
			  <ul class="mui-table-view">
              <li class="mui-table-view-cell">
              <?php $couponCount=Coupon::find()->andWhere(['user_guid'=>yii::$app->user->identity->user_guid,'status'=>1])?>
					<a class="mui-navigate-right" href="javascript:;" id="coupon">
					<span class="icon-bookmark"></span>	我的优惠券
					</a>
				</li>
              </ul>
			</form>
			
			
			<ul class="mui-table-view">
                            <?php $address=Address::findOne(['user_guid'=>yii::$app->user->identity->user_guid,'is_default'=>1]);
                                if(!empty($address)){?>
                           <li class="mui-table-view-cell ">收货地址:
                           <?= $address->province?>   <?= $address->city?>   <?= $address->district?>   <?= $address->address?>   <?= $address->company?>   <?= $address->name?>   <?= $address->phone?>
                            
                           </li>
                           <?php }?>
                           <li class="mui-table-view-cell " id="newAddress"><span class="glyphicon glyphicon-plus" style="color: rgb(255, 140, 60);"></span>新增收货地址</li>
              </ul>
              
            
			
    
    </div>
    
     <div class="col-xs-12">   
       <div class="panel-white">
       <h5>商品介绍</h5>
      <?= $model->desc?>
          </div>
  </div>
</div>
</div>

<?php if($model->count_sales<$model->number){?>
<div class="bottom-button">
<p> 共 <span id="total-p" class="green bold">1</span> 件宝贝 ,总计<span class="red-normal bold" id="total-m"> ￥ <?= $model->price?></span></p>
<button class="btn btn-danger btn-block buy-btn"  id="submit">立即购买</button>
</div>
<?php }else {?>
<button class="btn btn-default" >已售完</button>

<?php }?>


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
var price=<?= $model->price?>;
var maxNumber=<?=$model->number-$model->count_sales ?>;
$("#number").change(function(){
    var num=$(this).val();
    if(maxNumber<num){
    	num=maxNumber;
    }
$("#total-p").html(num);
$("#total-m").html("￥"+num*price);  
});

$("#number").blur(function(){
    var num=$(this).val();
$("#total-p").html(num);
$("#total-m").html("￥"+num*price);
    
});

var max=parseInt(<?= $model->number-$model->count_sales?>);
function check(){	
	var num=$("#number").val();
	if(num>max){
	    modalMsg("购买不能超过剩余份数");
	    return false;
	}

	showWaiting("正在提交,请稍候...");
	return true;
}

$('#submit').click(function(){
    $("#order-form").submit();
});

$('#newAddress').click(function(){
	 $('#AddressModal').modal('show');
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

