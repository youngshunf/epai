<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wenyuan\ueditor\Ueditor;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('@web/js/lrz.bundle.js', ['position'=> View::POS_HEAD]);
?>

    <?php $form = ActiveForm::begin(['id'=>'photo-form','options' => ['enctype' => 'multipart/form-data','onsubmit'=>'return check()']]); ?>
   
    <?= $form->field($model, 'title')->textInput(['maxlength' => 256]) ?>
     <?= $form->field($model, 'type')->dropDownList(['0'=>'跳转连接','1'=>'自定义页面']) ?>
     
     <div id="link-url">
     <?= $form->field($model, 'url')->textInput(['maxlength' => 256])?>
       </div>   
       <div id="photo-desc" >
         <div class="form-group">
       <label>自定义内容</label>
          <?= Ueditor::widget(['id'=>'desc',
                'model'=>$model,
                'attribute'=>'desc',
                'ucontent'=>$model->desc,
                ]);  ?>
        </div>
       </div>
       
        
      <div class="form-group">
        <label class="control-label"> 封面图片(推荐尺寸:1600*400)</label>
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

<script type="text/javascript">

$('#homephoto-type').change(function(){
    if($(this).val()==1){
        $('#link-url').addClass('hide');
    }else{
   	 $('#link-url').removeClass('hide');
    }
});

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