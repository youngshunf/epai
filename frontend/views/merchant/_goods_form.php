<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use wenyuan\ueditor\Ueditor;
use yii\web\View;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('@web/js/lrz.bundle.js', ['position'=> View::POS_HEAD]);
$user=yii::$app->user->identity;
?>

<div class="row">
 
    <?php $form = ActiveForm::begin(['id'=>'goods-form','options' => ['enctype' => 'multipart/form-data','onsubmit'=>'return check()']]); ?>
    <div class="col-md-12">
    <?= $form->field($model, 'name')->textInput(['maxlength' => 256]) ?>
  </div>  
   <div class="col-md-6">
     <?= $form->field($model, 'cateid')->dropDownList(ArrayHelper::map($cate, 'cateid', 'name')) ?>
    <?= $form->field($model, 'roundid')->dropDownList(ArrayHelper::map($round, 'id', 'name'),['prompt'=>'请选择']) ?>
    
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
     
    </div>
     
     <div class="col-md-6">


    <?= $form->field($model, 'start_price')->textInput() ?>

    <?= $form->field($model, 'delta_price')->textInput() ?>

 <?php if($user->merchant_role==2){?>
    <?= $form->field($model, 'lowest_deal_price')->textInput() ?>
  <?php }?>
        <?= $form->field($model, 'fixed_price')->textInput() ?>
    </div>
   <div class="col-md-12">
        <div class="form-group">
         <label>内容</label>
          <?= Ueditor::widget(['id'=>'goods-desc',
                'model'=>$model,
                'attribute'=>'desc',
                'ucontent'=>$model->desc,
                ]);  ?>
        </div>
        
      <div class="form-group">
        <label class="control-label"> 封面图片(推荐尺寸:720*400)</label>
        <div class="img-container">
        <?php if(empty($model->photo)){?>
                <div class="uploadify-button"> 
                </div>
        <?php }else{?>
            <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.'thumb/'.$model->photo?>" class="img-responsive">
        <?php }?>
        </div>
       <input type="file" name="photo"  class="hide"  id="photo">
       </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '确定', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
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

    /* if($("goods-form").hasClass('has-error')){
   	 modalMsg('请填写完再提交');
     return false;
    } */
	
	<?php if(empty($model->photo)){?>
    if(!$('#photo').val()){
        modalMsg('请上传照片');
        return false;
    }
    <?php }?>
  
    showWaiting('正在提交,请稍候...');
    return true;
}

</script>