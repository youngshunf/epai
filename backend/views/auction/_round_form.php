<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wenyuan\ueditor\Ueditor;
use yii\web\View;
use yii\helpers\Url;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('@web/js/lrz.bundle.js', ['position'=> View::POS_HEAD]);
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(['id'=>'round-form','options' => ['enctype' => 'multipart/form-data','onsubmit'=>'return check()']]); ?>
  
    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
     <?= $form->field($model, 'source')->textInput(['maxlength' => 255])->label('提供方(非必填)') ?>
     <?= $form->field($model, 'sort')->textInput(['maxlength' => 10])->label('排序(越大越靠前)') ?>
      <?= $form->field($model, 'start_time')->widget(DateTimePicker::className(),[
        'options' => ['placeholder' => '请选择时间'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd h:i'
        ]
    ]); ?>
      <?= $form->field($model, 'end_time')->widget(DateTimePicker::className(),[
        'options' => ['placeholder' => '请选择时间'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd h:i'
        ]
    ]); ?>
     <?= $form->field($model, 'desc')->textarea(['rows'=>'6'])?>
     <div class="form-group">
        <label class="control-label"> 封面图片</label>
        <div class="img-container">
        <?php if($model->isNewRecord||empty($model->photo)){?>
                <div class="uploadify-button"> 
                </div>
        <?php }else{?>
            <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.'thumb/'.$model->photo?>" class="img-responsive">
        <?php }?>
        </div>
       <input type="file" name="photo"  class="hide"  id="photo">
       </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">


$('.img-container').click(function(){
    $('#photo').click();
})

document.getElementById('photo').addEventListener('change', function () {
    var that = this;
    lrz(that.files[0], {
        width: 300
    })
        .then(function (rst) {
            var img        = new Image();            
            img.className='img-responsive';
            img.src = rst.base64;    
            img.onload = function () {
           	 $('.img-container').html(img);
            };                 
            return rst;
        });
});

function check(){
	<?php if($model->isNewRecord){?>
    if(!$('#photo').val()){
        modalMsg('请上传照片');
        return false;
    }
    <?php }?>
  
    showWaiting('正在提交,请稍候...');
    return true;
}

</script>