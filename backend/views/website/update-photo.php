<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\HomePhoto */

$this->title = '修改图片: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Home Photos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="home-photo-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_photo_form', [
        'model' => $model,
    ]) ?>

</div>
