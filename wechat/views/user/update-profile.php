<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Wish */

$this->title = '修改个人信息';

?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_user_form', [
        'model' => $model,
    ]) ?>

</div>
