<?php

use yii\helpers\Url;
use common\models\CommonUtil;
use common\models\LotteryRec;

?>
<?php if(!empty($model->goods)){?>
   <ul class="mui-table-view">
				<li class="mui-table-view-cell mui-media">			
					<img class="mui-media-object mui-pull-left"  src="<?= yii::getAlias('@photo').'/'.$model->goods->path.'thumb/'.$model->goods->photo?>" >
						
						<div class="mui-media-body">
							<p><?= $model->goods->name?></p>
							<p>总需: <?= $model->goods->price?></p>
							<p>本次参与: <span class="green"><?= LotteryRec::find()->andWhere(['user_guid'=>$model->user->user_guid,'goods_guid'=>$model->goods_guid])->count()?></span>人次
							<a class="pull-right" href="<?= Url::to(['view-lottery-code','goods_guid'=>$model->goods_guid])?>">查看号码</a>
							</p>
							<p><span class="grey"><?= CommonUtil::fomatTime($model->created_at)?></span></p>
						</div>
				
				</li>
    </ul>
  <?php }?>
