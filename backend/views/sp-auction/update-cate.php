<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = '修改: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '资讯分类', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view-cate', 'id' => $model->cateid]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_cate_form', [
        'model' => $model,
    ]) ?>

</div>
