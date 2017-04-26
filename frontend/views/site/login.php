<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container margin-content">
  

    <div class="row">
    	<div class="col-lg-3"></div>
        <div class="col-lg-6">
        <div class="panel-white">
        	  <h5><?= Html::encode($this->title) ?></h5>

   			 
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'username')->label('用户名') ?>
                <?= $form->field($model, 'password')->passwordInput()->label('密码') ?>
                <?= $form->field($model, 'rememberMe')->checkbox()->label('记住我') ?>
                <a class="pull-right" href="<?= yii::$app->urlManager->createUrl('site/request-password-reset')?>">忘记密码？</a>
                <div class="form-group">
                    <?= Html::submitButton('登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>
