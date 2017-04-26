<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

$this->title = "支付保证金";
$this->params['breadcrumbs'][] = ['label' => '天天易拍', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="container">
          <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>

    <div class="panel-white">
    <h5><?= Html::encode($this->title) ?></h5>

    <div class="row">
     <div class="col-lg-12">
     <div class="panel-white">
          <form action="<?= Url::to(['pay-guarantee'])?>" method="post" >
                 <p><label>商品名称:</label><?= CommonUtil::getDescByValue('user', 'role_id', $guaranteeFee->user_role)?>竞拍保证金</p>
                 <p><label>金额:</label><span class="red">￥<?= $order->amount?></span></p>
             <div class="form-group center">
         
             <input type="hidden" name="order-guid" value="<?= $order->order_guid?>">
                
                <button class="btn btn-success"  type="submit"  >立即支付</button>
       
              </div>  
              </form>
          
  </div>
  </div>
</div>
</div>
</div>


	     <!-- 出价 -->
<div class="modal fade" id="submit-bid" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               出价
            </h4>
         </div>
         <div class="modal-body">
       
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default"  id="modal-close"
               data-dismiss="modal">关闭
            </button>
         
         </div>
      </div><!-- /.modal-content -->
</div>
</div><!-- /.modal -->


<script type="text/javascript">





</script>
