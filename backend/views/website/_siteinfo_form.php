<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use wenyuan\ueditor\Ueditor;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('@web/js/lrz.bundle.js', ['position'=> View::POS_HEAD]);
?>

    <?php $form = ActiveForm::begin(['id'=>'siteinfo-form','options' => ['onsubmit'=>'return check()']]); ?>
   
   <div class="<?php if(!$model->isNewRecord) echo 'hide';?>">

    <?= $form->field($model, 'title')->textInput(['maxlength' => 256])->label('标题') ?>
    <?= $form->field($model, 'type')->dropDownList(['0'=>'拍品征集','1'=>'网上典当','2'=>'联系我们','3'=>'拍卖规则'])->label('类型')?>
    </div>
     
         <div class="form-group">
       
          <?= Ueditor::widget(['id'=>'info-content',
                'model'=>$model,
                'attribute'=>'content',
                'ucontent'=>$model->content,
                ]);  ?>
        </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '提交' : '保存', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
  
    <?php ActiveForm::end(); ?>

<script type="text/javascript">

function check(){


  
    showWaiting('正在提交,请稍候...');
    return true;
}

</script>