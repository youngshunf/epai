<?php

use yii\helpers\Url;
use common\models\CommonUtil;
use common\models\LotteryRec;

?>

   <ul class="mui-table-view">
				<li class="mui-table-view-cell mui-media">				
					
						<div class="mui-media-body">
							<p>优惠券码:<span class="red"><?= $model->coupon_code?> </span> 
							<span class="pull-right grey"><span class="green">[<?=  CommonUtil::getDescByValue('coupon', 'status', $model->status)?>] </span></span>
							 </p>						
							<p>优惠金额: ￥<span class="red"><?= $model->amount?> </span>, 	
							<?php if($model->min_amount<=1){?>
							无最低使用门槛
							<?php }else{?>
							满 ￥<span class="red"><?= $model->min_amount?></span> 可使用
							<?php }?>
							</p>
							<p>过期时间:<span class="red"><?= CommonUtil::fomatTime($model->end_time)?> </span> </p>						
							<p>优惠券类型:<span class="green"><?= CommonUtil::getDescByValue('coupon', 'type', $model->type)?> </span> </p>						
						</div>
				
				</li>
    </ul>
