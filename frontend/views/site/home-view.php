<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
<div class="row">
    <div class="col-md-12">
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <?= $model->desc?>

</div>
</div>
</div>
</div>