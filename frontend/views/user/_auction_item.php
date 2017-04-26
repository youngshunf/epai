<?php

use yii\helpers\Url;
use common\models\CommonUtil;
use common\models\LotteryRec;

?>

<?php if(!empty($model->auctionGoods)){?>
   <ul class="mui-table-view">
				<li class="mui-table-view-cell mui-media">				
					
						<img class="mui-media-object mui-pull-left"  src="<?= yii::getAlias('@photo').'/'.$model->auctionGoods->path.'thumb/'.$model->auctionGoods->photo?>" >
						
						<div class="mui-media-body">
						<p><?= $model->auctionGoods->name?></p>
							<p>
							<?php if($model->is_leading==1){?>
							<span class="auc-leading">领先</span>
							<?php }else{?>
							<span class="auc-out">出局</span>
							<?php }?>
							出价<span class="red-normal">￥<?= $model->price?></span>
							<?php if($model->is_agent==1){?>
							<span class="green">(代理)</span>
							<?php }?>
							</p>						
							<p>
							<span class="pull-right grey"><?= CommonUtil::fomatTime($model->created_at)?></span>
							</p>
						</div>
				
				</li>
    </ul>
  <?php }?>
