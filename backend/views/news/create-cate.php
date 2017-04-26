<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = '新增资讯分类';
$this->params['breadcrumbs'][] = ['label' => '新闻资讯', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_cate_form', [
        'model' => $model,
    ]) ?>

</div>
