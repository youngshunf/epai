<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\HomePhoto */

$this->title = '修改:'.$model->title;
$this->params['breadcrumbs'][] = ['label' => '网站管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="home-photo-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_siteinfo_form', [
        'model' => $model,
    ]) ?>

</div>
