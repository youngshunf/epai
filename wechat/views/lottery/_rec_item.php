<?php

use yii\helpers\Url;
use common\models\CommonUtil;
use common\models\LotteryRec;

?>

   <ul class="mui-table-view">
				<li class="mui-table-view-cell mui-media">				
					<?php if(empty($model->user->img_path)){?>
						    	<img class="mui-media-object mui-pull-left"  src="<?= yii::getAlias('@avatar')?>/unknown.jpg" >
						<?php }else{?>
						<img class="mui-media-object mui-pull-left"  src="<?= $model->user->img_path?>" >
						<?php }?>
						<div class="mui-media-body">
							<p><a><?= !empty($model->user->nick)?$model->user->nick:CommonUtil::truncateMobile($model->user->mobile)?></a> <!-- <span>(IP:<?= $model->ip?>)</span> --></p>
							<p>参与了<span class="green"><?= LotteryRec::find()->andWhere(['user_guid'=>$model->user->user_guid,'goods_guid'=>$model->goods_guid])->count()?></span>人次
							<span class="pull-right"><?= CommonUtil::fomatTime($model->created_at)?></span>
							</p>
						</div>
				
				</li>
    </ul>
  
