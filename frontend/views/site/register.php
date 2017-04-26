<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '注册';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container margin-content">
  

    <div class="row">
    	<div class="col-lg-3"></div>
        <div class="col-lg-6">
      <div class="panel-white">
        	  <h5><?= Html::encode($this->title) ?></h5>   			 
            <?php $form = ActiveForm::begin([
                'id' => 'register-form',      
               'options'=>[ 'onsubmit'=>'return check()']  
            ]); ?>    
                <?= $form->field($model, 'email',['enableAjaxValidation'=>true])->label('邮箱') ?>
                <?= $form->field($model, 'mobile',['enableAjaxValidation'=>true],['maxLength'=>11])->label('手机号') ?>                
                <?= $form->field($model, 'password')->passwordInput()->label('密码') ?>
                 <?= $form->field($model, 'password2')->passwordInput()->label('确认密码') ?>
                                     
                 <?= $form->field($model, 'agree_rules')->checkbox()->label('已阅读并同意《会员条款》') ?>         
               <p class="pull-right">  <a href="<?= yii::$app->urlManager->createUrl('site/membership')?>" target="_blank">阅读会员条款</a></p>                                   
                 <?= Html::submitButton('注册', ['class' => 'btn btn-success ', 'name' => 'register-button','id'=>'register']) ?> 
                              
                </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>
<script>
function check(){
  	
	if( $(".has-error").length>0){		
		modalMsg("请填写正确再提交!");
	    return false;
	}	

    if(!$("#registerform-email").val()||!$("#registerform-mobile").val()||!$("#registerform-password").val()
    	||!$("#registerform-password2").val()||!$("#registerform-agree_rules").is(':checked')){
    	modalMsg("请填写完整再提交!");
	    return false;
    }
	
	showWaiting("正在注册,请稍后...");
	return true;
}
            
 </script>