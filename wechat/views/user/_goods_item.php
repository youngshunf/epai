<?php

use yii\helpers\Url;
use common\models\CommonUtil;
use common\models\LotteryRec;

?>

<?php if(!empty($model->auctionGoods)){?>
   <ul class="mui-table-view">
				<li class="mui-table-view-cell mui-media">				
					<a href="<?= Url::to(['auction/view','id'=>$model->auctionGoods->id])?>">
						<img class="mui-media-object mui-pull-left"  src="<?= yii::getAlias('@photo').'/'.$model->auctionGoods->path.'thumb/'.$model->auctionGoods->photo?>" >
						<div class="mui-media-body">
						<p><?= $model->auctionGoods->name?></p>
							<p>
							当前价格:<?=$model->auctionGoods->current_price?>
							</p>
							<p>
							<?php 
            				$now=time();
            				if($now>=$model->auctionGoods->start_time&&$now<=$model->auctionGoods->end_time){
            				?>	
            				状态:<span class="red">正在拍卖</span>
            				<?php }elseif ($now<$model->auctionGoods->start_time){?>
            				状态:<span class="red">正在预展</span>
            				<?php }elseif ($now>$model->auctionGoods->end_time){?>
            				状态:<span class="red">已结束</span>
            				<?php }?>
							</p>						
							<p>
							<span class="pull-right grey"><?= CommonUtil::fomatTime($model->created_at)?></span>
							</p>
						</div>
						</a>
				</li>
    </ul>
  <?php }?>
