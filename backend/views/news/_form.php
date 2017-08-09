<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wenyuan\ueditor\Ueditor;
use yii\web\View;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('@web/js/lrz.bundle.js', ['position'=> View::POS_HEAD]);
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(['id'=>'news-form','options' => ['enctype' => 'multipart/form-data','onsubmit'=>'return check()']]); ?>
  
    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
      <?= $form->field($model, 'cateid')->dropDownList(ArrayHelper::map($cate, 'cateid', 'name'))->label('资讯分类')?>
    <div class="row">
    <div class="col-md-6">
    <?= $form->field($model, 'is_web')->dropDownList(['1'=>'是','0'=>'否']) ->label('是否发布到网站')?>
    </div>
    <div class="col-md-6">
       <?= $form->field($model, 'is_wechat')->dropDownList(['1'=>'是','0'=>'否']) ->label('是否发布到微信')?>
    </div>
    </div>
    
    <div class="form-group">
         <label>内容</label>
          <?= Ueditor::widget(['id'=>'news-content',
                'model'=>$model,
                'attribute'=>'content',
                'ucontent'=>$model->content,
                ]);  ?>
        </div>
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
        <?= Html::submitButton($model->isNewRecord ? '提交' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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
	
 /*    if($("#news-form").hasClass('has-error')){
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