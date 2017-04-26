<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\HomePhoto */

$this->title = '增加网站信息';
$this->params['breadcrumbs'][] = ['label' => '首页图片', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="home-photo-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_siteinfo_form', [
        'model' => $model,
    ]) ?>

</div>
