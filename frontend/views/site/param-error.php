<?php

use yii\helpers\Html;

$this->title = '访问错误';
?>
<div class="panel-white">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="alert alert-danger">
      <h2>您未登录,访问被阻止,请从微信公众号中打开连接进行操作.</h2>
    </div>

 

</div>
