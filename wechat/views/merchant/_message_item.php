<?php

use common\models\CommonUtil;
use yii\helpers\Url;

?>

<ul class="mui-table-view" style="margin-top: 8px">
				<li class="mui-table-view-cell mui-media">
					
						<img class="mui-media-object mui-pull-left" src="<?=yii::getAlias('@avatar')?>/sysmanager.png">
					
						<div class="mui-media-body">
							系统管理员
							<p><?= $model->content?></p>
							<p><span class="pull-right"><?= CommonUtil::fomatTime($model->created_at)?></span></p>
						</div>
					
				</li>
			
	</ul>