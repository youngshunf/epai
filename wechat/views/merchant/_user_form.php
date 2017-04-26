<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Wish */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wish-form">

    <?php $form = ActiveForm::begin(['id'=>'user-form','options' => ['onsubmit'=>'return check()']]); ?>

   <?= $form->field($model, 'nick')->textInput(['maxlength' => 30]) ?>
   
   <?= $form->field($model, 'post')->textInput(['maxlength' => 30]) ?>
   <?= $form->field($model, 'sex')->dropDownList(['1'=>'男','2'=>'女']) ?>
    
       <div class="form-froup">
    <label class="label-control">生日</label>
    <input type="date" name="birthday"  class="form-control"  id="birthday" placeholder="请选择生日">
    </div>
    
       <?= $form->field($model, 'province')->textInput(['maxlength' => 20]) ?>
          <?= $form->field($model, 'city')->textInput(['maxlength' => 20]) ?>
    <?= $form->field($model, 'region')->textInput(['maxlength' => 20]) ?>
    
    
    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'weixin')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'qq')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 64]) ?>
      <?= $form->field($model, 'ideal')->textarea(['rows'=>'3'])?>
     <?= $form->field($model, 'motto')->textarea(['rows'=>'3'])?>
    <div class="form-group center">
        <?= Html::submitButton( '提交' , ['class' => 'btn btn-success ' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
  function check(){
	  if( $(".has-error").length>0){		
			modalMsg("请填写正确再提交!");
		    return false;
		}	
	  
	    if(!$("#birthday").val()){
	        modalMsg('请选择生日');
	        return false;
	    }

	    var e=0;
	    $("input[type=text]").each(function(){
	        if(!$(this).val()){
	            e++;
	        }
	    });
	    if(e>0){
	    	modalMsg("请填写完整再提交!");
	        return false;
	    }

	    showWaiting("正在提交,请稍后...");
	    return true;
  }
</script>
