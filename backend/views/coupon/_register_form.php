<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Coupon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupon-form">

    <?php $form = ActiveForm::begin(['id'=>'coupon-form','options' => ['onsubmit'=>'return check()']]); ?>
	<?= $form->field($model, 'is_open')->dropDownList(['0'=>'关闭','1'=>'开启']) ?>
    <?= $form->field($model, 'amount')->textInput(['maxlength' => 10]) ?>

	<?= $form->field($model, 'expire_day')->textInput(['maxlength' => 10]) ?>
    <?= $form->field($model, 'min_amount')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => 255]) ?>
    

    <div class="form-group">
        <?= Html::submitButton('提交' , ['class' =>  'btn btn-success' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
function check(){
	var required=0;
	$('.required').find('input').each(function(){
		if(!$(this).val()){
			required++;
		}
	});
	
	if(required!=0){
		modalMsg('请填写完整再提交!');
		return false;
	}

	showWaiting('正在提交,请稍后...');
	return true;

	
}
</script>