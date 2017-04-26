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
               
				<?php if($model->type==Order::TYPE_GUARANTEE){?>
    				 <li class="mui-table-view-cell mui-media">	
                    <p><span class="mui-badge mui-badge-primary">保证金订单</span><span class="pull-right red-sm"><?= CommonUtil::getDescByValue('order', 'status', $model->status)?></span></p>
                    </li>
    				<li class="mui-table-view-cell mui-media">									
					<p><?= $model->goods_name?></p> 	
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
                        <p><span class="grey">￥<?= $goods->price?></span>
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
                        <p><span>￥<?= $model->amount?></span>
                     </p>
                                 
						</div>
						</li>
						<li class="mui-table-view-cell mui-media">
				<p><span class="pull-right"> 共 <?= $model->number?> 件商品 , 合计 <i class="red-sm">￥<?= $model->amount?></i> </span></p>
				</li>
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
                        <p><span>￥<?= $goods->price?></span>
                     </p>
                                 
						</div>
					</li>
					<li class="mui-table-view-cell mui-media">
				<p><span class="pull-right"> 共 <?= $model->number?> 件商品 , 合计 <i class="red-sm">￥<?= $model->amount?></i> </span></p>
				</li>
					<?php }?>
					<?php }?>
				
    </ul>
  
