<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\widgets\Breadcrumbs;

$this->title = '支付结果';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
<div class="row">
<div class="col-md-12">

  <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
</div>

<div class="col-md-12">
<div class="panel-white">

                
    <h5><?= Html::encode($this->title) ?></h5>

      <div class="center">
      <?php if($order->is_pay==0){?>
      <br>
        <h1 class="center" ><i class="icon-close time"></i> 支付失败!</h1>
        <br>  
      
      <?php }else{?>
      <br>
        <h1 class="center" ><i class="icon-ok time"></i> 支付成功!</h1>
        <br>  
        <?php }?>
      </div>
      <p class="bold">订单信息:</p>
      
        <?= DetailView::widget([
        'model' => $order,
        'attributes' => [
        ['attribute'=>'商品名称','value'=>$order->goods_name],
        ['attribute'=>'支付金额','value'=>$order->amount],
          ['attribute'=>'数量','value'=>$order->number],
           ['attribute'=>'时间','value'=>CommonUtil::fomatTime($order->pay_time)],
        ],
    ]) ?>
      <div class="center">
      <a class="btn btn-info" href="<?= yii::$app->getUser()->getReturnUrl()?>">返回</a>
      </div>
    </div>
</div>
</div>
</div>
