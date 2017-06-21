<?php

use yii\helpers\Url;
use common\models\CommonUtil;
use common\models\LotteryRec;
use common\models\Order;
use common\models\LotteryGoods;
use common\models\AuctionGoods;
use common\models\MallGoods;

?>

   <ul class="mui-table-view">
               <a href="<?= Url::to(['site/pay-order','order_guid'=>$model->order_guid])?>" style="color:#333">
				<?php if($model->type==Order::TYPE_GUARANTEE){?>
    				 <li class="mui-table-view-cell mui-media">	
                    <p><span class="mui-badge mui-badge-primary">保证金订单</span><span class="pull-right red-sm"><?= CommonUtil::getDescByValue('order', 'status', $model->status)?></span></p>
                    </li>
    				<li class="mui-table-view-cell mui-media">									
					<p><?= $model->goods_name?></p> 	
					<p><span>订单编号<?= $model->orderno?></span></p>
					</li>	
					<li class="mui-table-view-cell mui-media">
				<p><span class="pull-right"> 共 <?= $model->number?> 件商品 , 合计 <i class="red-sm">￥<?= $model->amount?></i> </span></p>
				</li>
					<?php }elseif ($model->type==Order::TYPE_LOTTERY){
					$goods=LotteryGoods::findOne(['goods_guid'=>$model->biz_guid]);
					if(!empty($goods)){
					?>
					 <li class="mui-table-view-cell mui-media">	
                    <p><span class="mui-badge mui-badge-success">夺宝订单</span><span class="pull-right red-sm"><?= CommonUtil::getDescByValue('order', 'status', $model->status)?></span></p>
                    </li>
    				<li class="mui-table-view-cell mui-media">	
					<img class="mui-media-object mui-pull-left"  src="<?= yii::getAlias('@photo').'/'.$goods->path.'thumb/'.$goods->photo?>" >
					<div class="mui-media-body">
				       	<p class="bold"><?= $goods->name?></p>
                        <p><span class="grey">￥<?= $goods->price?></span></p>
                        <p><span>订单编号: <?= $model->orderno?></span>
                     </p>
                                 
						</div>
						</li>
						<li class="mui-table-view-cell mui-media">
				<p><span class="pull-right"> 共 <?= $model->number?> 件商品 , 合计 <i class="red-sm">￥<?= $model->amount?></i> </span></p>
				</li>
						<?php }?>
					<?php }elseif ($model->type==Order::TYPE_AUCTION){
					$goods=AuctionGoods::findOne(['goods_guid'=>$model->biz_guid]);
					if(!empty($goods)){
					?>
					 <li class="mui-table-view-cell mui-media">	
                <p><span class="mui-badge mui-badge-warning">拍卖订单</span> <span class="pull-right red-sm"><?= CommonUtil::getDescByValue('order', 'status', $model->status)?></span></p>
                </li>
				<li class="mui-table-view-cell mui-media">	
					<img class="mui-media-object mui-pull-left"  src="<?= yii::getAlias('@photo').'/'.$goods->path.'thumb/'.$goods->photo?>" >
					<div class="mui-media-body">
				       	<p class="bold"><?= $goods->name?></p>
                        <p><span >￥<?= $model->amount?></span></p>
                        <p><span >订单编号 ： <?= $model->orderno?></span></p>
                        <?php if($model->status>1 && $model->status <=3){?>
                        <p><span >快递公司 ： <?= $model->express_company?></span></p>
                         <p><span >快递单号 ： <?= $model->express_number?></span>
                         <a class="btn btn-info"  href="http://www.kuaidi100.com/chaxun?com=<?= $model->express_company?>&nu=<?= $model->express_number?>"  target="_blank">查询</a>
                         </p>        
                         <?php }?>
						</div>
						</li>
						<li class="mui-table-view-cell mui-media">
						<p><span class="pull-right"> 共 <?= $model->number?> 件商品 , 合计 <i class="red-sm">￥<?= $model->amount?></i> </span></p>
						</li>
        						<?php if($model->status==2){?>
        					<li class="mui-table-view-cell mui-media">
        					<p><a class="mui-btn mui-btn-danger pull-right" href="<?= Url::to(['confirm-goods','id'=>$model->id])?>">确认收货</a></p>
        					</li>
        					<?php }?>
						<?php }?>
					<?php }elseif ($model->type==Order::TYPE_MALL){
					    $goods=MallGoods::findOne(['goods_guid'=>$model->biz_guid]);
					    if(!empty($goods)){
					?>
					 <li class="mui-table-view-cell mui-media">	
                <p><span class="mui-badge mui-badge-danger">商城订单</span><span class="pull-right red-sm"><?= CommonUtil::getDescByValue('order', 'status', $model->status)?></span></p>
                </li>
				<li class="mui-table-view-cell mui-media">	
					<img class="mui-media-object mui-pull-left"  src="<?= yii::getAlias('@photo').'/'.$goods->path.'thumb/'.$goods->photo?>" >
					<div class="mui-media-body">
				       	<p class="bold"><?= $goods->name?></p>
                        <p><span>￥<?= $goods->price?></span></p>
                        <p><span>订单编号<?= $model->orderno?></span>
                     </p>
                                 
						</div>
					</li>
					<li class="mui-table-view-cell mui-media">
				<p><span class="pull-right"> 共 <?= $model->number?> 件商品 , 合计 <i class="red-sm">￥<?= $model->amount?></i> </span></p>
				</li>
					<?php if($model->status==2){?>
					<li class="mui-table-view-cell mui-media">
					<p><a class="mui-btn mui-btn-danger pull-right" href="<?= Url::to(['confirm-goods','id'=>$model->id])?>">确认收货</a></p>
					</li>
					<?php }?>
					<?php }?>
					<?php }?>
				</a>
    </ul>
  
