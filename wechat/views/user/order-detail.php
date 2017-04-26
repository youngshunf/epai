<?php

use yii\helpers\Html;
use common\models\CommonUtil;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Wish */

$this->title ="支持愿望-".$model->wish_title;

?>
<style>
.panel-white {

   border-radius:0px; 
}
label {
  color: rgb(12, 136, 173);
}
</style>
<div class="c_img">
            <img alt="<?= $model->wish_title?>" src="<?= yii::getAlias('@photo').'/'.$model->path.'standard/'.$model->photo?>" class="img-responsive">
            <div class="c_words">
            进入<?=$model['user']['nick']?>的相册
            </div>
    </div>
<div class="panel-white">
  
         <p ><label class="label-control">支持<?= $model['user']['nick']?>的愿望:</label><?=$model['wish_title']?></p>
        <p ><label class="label-control">支持金额:</label><span class="red">￥<?=$order['support_amount']?></span></p>        
        <p ><label class="label-control">联系电话:</label><?=$order['mobile']?></p>
 
          
   
   
</div>

<a class="btn btn-danger btn-block btn-lg" id="support">立即支付</a>


 