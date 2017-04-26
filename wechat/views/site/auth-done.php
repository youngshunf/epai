<?php

use yii\helpers\Html;

$this->title = '您的身份已经验证通过';
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="alert alert-success">
      <h4>您的身份已经验证通过,验证信息如下:</h4>
      <p>姓名:<?= $_SESSION['user']['real_name']?>
      <p>工号:<?= $_SESSION['user']['work_number']?>
    </div>

 

</div>
