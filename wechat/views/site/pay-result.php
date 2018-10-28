<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use common\models\LotteryRec;

$this->title = '支付结果';
?>

<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

      <div class="center">
      <?php if($order->is_pay==0){?>
        <h1 class="center" ><i class="icon-remove red-normal"></i> 支付失败!</h1>
        <br>  
      <?php }else{?>
        <h1 class="center" ><i class="icon-ok time"></i> 支付成功!</h1>
        <br>  
        <?php }?>
      </div>
      <h3 >订单信息:</h3>
      <ul class="mui-table-view">
      <li class="mui-table-view-cell">      <p><span class="bold">商品名称:</span> <?=$order->goods_name ?></p></li>
       <li class="mui-table-view-cell">    <p><span class="bold">数量: </span> <i class="green"><?=$order->number ?></i></p></li>
        <li class="mui-table-view-cell">    <p><span class="bold">商品金额: </span> <i class="red-normal"> ￥ <?=$order->total_amount ?></i></p></li>
        <?php if($order->seller_fee>0){?>
         <li class="mui-table-view-cell">    <p><span class="bold">买家佣金: </span> <i class="red-normal"> + ￥<?=$order->seller_fee ?></i></p></li>
       <?php }?>
       <?php if($order->discount_amount>0){?>
          <li class="mui-table-view-cell">    <p><span class="bold">优惠金额: </span> <i class="red-normal"> - ￥<?=$order->discount_amount?></i></p></li>
      <?php }?>
       <li class="mui-table-view-cell">    <p><span class="bold">支付金额:</span> <i class="red">￥<?=$order->amount ?></i></p></li>
      
       <li class="mui-table-view-cell">       <p><span class="bold">支付时间:</span><?=CommonUtil::fomatTime($order->pay_time) ?></p></li>
        <?php if($order->is_pay==1&&$order->type==3){?>
               <li class="mui-table-view-cell">
               <p class="bold">抽奖号码:</p>
        <?php
        $lotteryRec=LotteryRec::findAll(['order_guid'=>$order->order_guid]);
        foreach ($lotteryRec as $v){
        ?>
       <p><?= $v->lottery_code?></p>
        <?php }?>
        </li>
        <?php }?>
      </ul>
      
    
      <div class="center">
       <?php if($order->is_pay==0){?>
        <a class="btn btn-success" href="<?=Url::to(['pay-order','order_guid'=>$order->order_guid])?>">重新支付</a>
      <?php }?>
      <a class="btn btn-info" href="<?=yii::$app->getUser()->getReturnUrl()?>">返回</a>
     
      </div>
    </div>

