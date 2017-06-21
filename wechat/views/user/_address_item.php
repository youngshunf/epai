<?php

use yii\helpers\Url;
use common\models\CommonUtil;
use common\models\LotteryRec;

?>

   <ul class="mui-table-view">
				<li class="mui-table-view-cell mui-media">			
						<div class="">
						<?php if($model->is_default==1){?>
						<div class="pull-right green">[默认]</div>
						<?php }?>
							<p>省份: <?= $model->province?></p>
							<p>城市: <?= $model->city?></p>
							<p>地区: <?= $model->district?></p>
							<p>邮编: <?= $model->postcode?></p>
							<p>地址: <?= $model->address ?></p>
							<p>收件人: <?= $model->name ?></p>
							<p>联系电话: <?= $model->phone ?></p>
							
							<?php if($model->is_default==0){?>
						<a class="pull-right " href="<?= Url::to(['set-default-address','id'=>$model->id])?>">设为默认</a>
						<?php }?>
						</div>
						
				</li>
    </ul>
 
