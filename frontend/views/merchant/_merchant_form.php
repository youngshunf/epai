<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\Wish */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('@web/js/lrz.bundle.js', ['position'=> View::POS_HEAD]);

?>


    <?php $form = ActiveForm::begin(['id'=>'user-form','options' => ['enctype' => 'multipart/form-data','onsubmit'=>'return check()']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
     <?= $form->field($model, 'email')->textInput(['maxlength' => 32]) ?>
    <?php //$form->field($model, 'merchant_type')->dropDownList(['1'=>'个人','2'=>'企业/机构'])->label('卖家类型') ?>
    <?= $form->field($model, 'id_code')->textInput(['maxlength' => 32]) ?>
    <?= $form->field($model, 'country')->textInput(['maxlength' => 32]) ?>
    <?= $form->field($model, 'province')->textInput(['maxlength' => 32]) ?>
    <?= $form->field($model, 'city')->textInput(['maxlength' => 32]) ?>
    <?= $form->field($model, 'home_address')->textInput(['maxlength' => 32]) ?>
    <?= $form->field($model, 'company_address')->textInput(['maxlength' => 32]) ?>
  
  <div class="row">
 <div class="col-md-4">
    <div class="form-group center">
<label class="control-label">身份证正面照片</label>
    <div class="img-container">
            <div class="uploadify-button"> 
            </div>
    </div>
   <input type="file" name="photo1"  class="hide"  id="photo1">
    </div>
    </div>

    <div class="col-md-4">
    <div class="form-group center">
<label class="control-label">身份证背面照片</label>
    <div class="img-container">
            <div class="uploadify-button"> 
            </div>
    </div>
   <input type="file" name="photo2"  class="hide"  id="photo2">
    </div>
    </div>
    
    <div class="col-md-4">
    <div class="form-group center">
<label class="control-label">手持身份证照片</label>
    <div class="img-container">
            <div class="uploadify-button"> 
            </div>
    </div>
   <input type="file" name="photo3"  class="hide"  id="photo3">
    </div>
    </div>


</div>


    <div class="form-group center">
        <?= Html::submitButton( '提交' , ['class' => 'btn btn-success ' ]) ?>
    </div>

    <?php ActiveForm::end(); ?>


 <script type="text/javascript">

$('.img-container').click(function(){
    $(this).parent().find('input[type=file]').click();
})

$('input[type=file]').change(function () {
    var that = $(this);
    var files=that.prop('files');
    lrz(files[0], {
        width: 1024
    })
        .then(function (rst) {
                   
            var img        = new Image();            
            img.className='img-responsive';
            img.src = rst.base64;    
            img.onload = function () {
           	 that.parent().find('.img-container').html(img);
            };                 
            return rst;
        });
});

function check(){

    var required=0;
    $('.required').find('input').each(function(){
        if(!$(this).val()){
            required++;
        }
    });
    if(required!=0){
        modalMsg('请填完信息再提交！');
        return false;
    }
    var photo=0;
    $('input[type=file]').each(function(){
       	 if(!$(this).val()){
             photo++;
         }
    });

    if(photo!=0){
        modalMsg('上传身份证照片！');
        return false;
    }
  
    showWaiting('正在提交,请稍候...');
    return true;
}

</script>   