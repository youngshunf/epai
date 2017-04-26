<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = '新增拍卖专场';
$this->params['breadcrumbs'][] = ['label' => '拍品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_round_form', [
        'model' => $model,
    ]) ?>
