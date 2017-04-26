<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
$this->title = '重置密码: ' . ' ' . $model->mobile;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mobile, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '重置密码';
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 32])->label('请输入新密码') ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '重置密码' : '重置密码', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
