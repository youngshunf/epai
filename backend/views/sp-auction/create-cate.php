<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = '新增拍品分类';
$this->params['breadcrumbs'][] = ['label' => '新闻资讯', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_cate_form', [
        'model' => $model,
    ]) ?>
