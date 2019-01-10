<?php

use yii\web\View;
use yii\widgets\LinkPager;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\AuctionGoods;

?>

<a href="<?= Url::to(['round-view','id'=>$model['id']])?>">
    <div class="row auction-item">
    <div class="col-xs-4">
		<img src="<?= yii::getAlias('@photo').'/'.$model->path.'thumb/'.$model->photo?>"  class="img-responsive">
    </div>
        <div class="col-xs-8">
            <ul class="auction">
			<li class="pai-item">
				<div class="pai-content">
				 <h5 >
				 <p class="ellipsis clamp-2"><?= $model->name?></p>
				
				 </h5>
				  <p> &nbsp;<span class=" sub-txt">共<i class="red"><?= AuctionGoods::find()->andWhere(['roundid'=>$model->id])->count()?></i>个拍品, <span > <i class="red"><?=AuctionGoods::find()->andWhere(['roundid'=>$model->id])->sum(' count_auction ') ?></i>次竞拍</span>	</span></p>
				  <?php
                    $now=time();
                 if($now>=$model->start_time&&$now<=$model->end_time){?>
				 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$model->end_time)?>" >
				 &nbsp;<span class="countdown-text">距结束</span>&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
                <span class="btn btn-danger btn-sm  pull-right">竞拍中</span>
				 <div class="clear"></div>
                 </div>
                 <?php }elseif ( $now>$model->end_time){?>
                  <div  >
        				 &nbsp;<span class="btn btn-default btn-sm  pull-right">已结束</span>&nbsp;&nbsp;
        				 <div class="clear"></div>
                    </div>
          
          <?php }elseif ($now<$model->start_time){?>
          	 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$model->start_time)?>" >
				 &nbsp;<span class="countdown-text">距开始</span>&nbsp;&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
                <span class="btn btn-success btn-sm  pull-right">即将开始</span>
				 <div class="clear"></div>
                 </div>
          
          <?php }?>
            
					
					<?php if(!empty($model->source)){?>
					<p>拍品提供:  <?=$model->source?></p>
					<?php }?>
					</div>			 				 
				</div>			
			
			
			</li>						
			</ul>				 				 
        </div>
   
</a>

  
