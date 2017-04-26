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
                <?= $form->field($model, 'username')->label('手机号') ?>
                <p><span class="red">*</span>请输入真实手机号,方便我们与您联系</p>
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
