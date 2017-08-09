<?php

use yii\helpers\Url;
use common\models\CommonUtil;
use common\models\LotteryRec;

?>

   <ul class="mui-table-view">
				<li class="mui-table-view-cell mui-media">				
					<?php if(empty($model->user->img_path)){?>
					       <?php if($model->user->role_id==0){?>
					           <img class="mui-media-object mui-pull-left"  src="<?= yii::getAlias('@avatar')?>/virtual/<?= rand(1, 20)?>.png" >
						      <?php }else{?>
						    	<img class="mui-media-object mui-pull-left"  src="<?= yii::getAlias('@avatar')?>/unknown.jpg" >
						      <?php }?>
						<?php }else{?>
						<img class="mui-media-object mui-pull-left"  src="<?= $model->user->img_path?>" >
						<?php }?>
						<div class="mui-media-body">
						
							<p><a><?= !empty($model->user->nick)?$model->user->nick:$model->user->mobile?></a> <span>(IP:<?= $model->ip?>)</span></p>
							<p>抽奖号码:<span class="green"><?= $model->lottery_code?></span>
							<span class="pull-right"><?= CommonUtil::fomatTime($model->created_at)?></span>
							</p>
						</div>
				
				</li>
    </ul>
  
