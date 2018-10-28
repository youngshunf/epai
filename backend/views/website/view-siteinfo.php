<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\HomePhoto */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '首页图片', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="home-photo-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('修改', ['update-siteinfo', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>
  

   <?= $model->content?>
</div>
