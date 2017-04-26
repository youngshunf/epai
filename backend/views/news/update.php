<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = '修改: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '新闻资讯', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->newsid]];
$this->params['breadcrumbs'][] = '修改';
?>


    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'cate'=>$cate
    ]) ?>

