<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '联系我们';
?>

<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <?= $model->content?>

</div>
