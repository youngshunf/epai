<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '绑定手机号';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
  

    <div class="row">
    	<div class="col-lg-3"></div>
        <div class="col-lg-6">
        <div class="panel-white">
        	  <h5><?= Html::encode($this->title) ?></h5>

   			 
            <?php $form = ActiveForm::begin(['id' => 'band-form']); ?>
                <div class="form-group">
                <label>手机号</label>
                <input type="tel" name="mobile" id="mobile" class="form-control">
                </div>
                <p><span class="red">*</span>请输入真实手机号,方便我们与您联系</p>
                <div class="form-group">
                 <label>验证码</label>
                 <input class="form-control" type="text" name="verfycode">
                 <p><button  id="sendVerify" type="button" class="pull-right btn btn-success">发送验证码</button></p>
                </div>  
                <div class="clear"></div>
                <?= $form->field($model, 'password')->passwordInput()->label('密码') ?>  
                       
                <div class="form-group center">
                    <?= Html::submitButton('立即绑定', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>

<script>

$('#sendVerify').click(function(){
	sendVerifyCode();
});

function sendVerifyCode(){
	var mobile=$('#mobile').val();
	var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
	 if (!reg.test(mobile)) {
	 	modalMsg("请输入正确的手机号码!");
	 	return ;
	 }
	 var data={
	 	mobile:mobile
	 };
	 
	 $.ajax({
	 	type:"post",
	 	url:'/site/send-verify-code',
	 	data:{
	 		data:data
	 	},
	 	success:function(rs){
	 		modalMsg("发送验证码返回数据:"+rs);
	 		
	 		if(rs=="sent"){
	 			modalMsg('验证码已发送，请注意查收!');
	 			var sendVerify=$("#sendVerify");
	 			sendVerify.removeClass('btn-success');
	 			sendVerify.addClass('btn-warning');
	 			sendVerify.attr('disabled','disabled');
	 			sendVerify.unbind('click');
	 			countDown(120);
	 		}else{
	 			modalMsg(rs);
	 		}
	 	},
	 	error:function(e){
	 		modalMsg("发送失败:"+e.status);
	 		console.log("发送失败:"+e.status);
	 	}
	 });
}
var intervalid; 
function countDown(s){
	var i = s; 
	
	intervalid = setInterval(fun, 1000); 
	function fun() { 
	if (i == 0) { 
	sendAgain();
	return;
	} 
	$('#sendVerify').html(i+'秒');
	i--; 
	} 
}

function sendAgain(){
	$('#sendVerify').removeClass('btn-warning');
	$('#sendVerify').addClass('btn-success');
	$('#sendVerify').html('重新获取');
	$('#sendVerify').removeAttr('disabled');
	clearInterval(intervalid);
	$('#sendVerify').bind('click',sendVerifyCode);
}



</script>