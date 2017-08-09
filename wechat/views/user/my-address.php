<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchLotteryGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '收货地址';
$this->registerJsFile('@web/js/PCASClass.js',['position'=> View::POS_HEAD]);

?>
<div class="panel-white">
<h5>收货地址</h5>
	<ul class="mui-table-view">
	<li class="mui-table-view-cell " id="newAddress"><span class="glyphicon glyphicon-plus" style="color: rgb(255, 140, 60);"></span>新增收货地址</li>
	</ul>
</div>

    <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_address_item',            
           'layout'=>"{items}\n{pager}"
      ])?>



<!-- 新增收货地址-->
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
       
            <form action="<?= Url::to(['user/new-address'])?>" method="post" onsubmit="return checkAddress()">
            	 <input type="hidden" name="_csrf"  value="<?= yii::$app->request->csrfToken?>">
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

<script>

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