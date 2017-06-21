<?php

use yii\web\View;
use yii\widgets\LinkPager;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\AuctionGoods;

?>

<a href="<?= Url::to(['round-view','id'=>$model['id']])?>">
    <div class="row">
    <div class="col-md-6">
    <a href="<?= Url::to(['round-view','id'=>$model->id])?>">
		<img src="<?= yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo?>"  class="img-responsive">
		</a>	
    </div>
        <div class="col-md-6">
            <ul class="auction">
			<li class="pai-item">
				<div class="pai-content">
				 <h5 class="ellipsis"><?= $model->name?>
				 <span class="pull-right">共<i class="red"><?= AuctionGoods::find()->andWhere(['roundid'=>$model->id])->count()?></i>个拍品, <span > <i class="red"><?=AuctionGoods::find()->andWhere(['roundid'=>$model->id])->sum(' count_auction ') ?></i>次竞拍</span>	</span>
				 </h5>
				  
				  <?php
                    $now=time();
                 if($now>=$model->start_time&&$now<=$model->end_time){?>
				 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$model->end_time)?>" >
				 &nbsp;<span class="countdown-text">距结束</span>&nbsp;&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
                <span class="btn btn-danger  pull-right">竞拍中</span>
				 <div class="clear"></div>
                 </div>
                 <?php }elseif ($now>$model->end_time){?>
                  <div  >
        				 &nbsp;<span class="btn btn-default  pull-right">已结束</span>&nbsp;&nbsp;
        				 <div class="clear"></div>
                    </div>
          
          <?php }elseif ($now<$model->start_time){?>
          	 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$model->start_time)?>" >
				 &nbsp;<span class="countdown-text">距开始</span>&nbsp;&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
                <span class="btn btn-success  pull-right">即将开始</span>
				 <div class="clear"></div>
                 </div>
          
          <?php }?>
                 <br>
					<div class="row">
					<?php $roundGoods=AuctionGoods::find()->andWhere(['roundid'=>$model->id])->limit(4)->all();
					foreach ($roundGoods as $k=>$v){
					?>
					<div class="col-md-3 col-xs-6">
					    <a href="<?= Url::to(['view','id'=>$v->id])?>">
					    <div class="c_img">
                		<img src="<?= yii::getAlias('@photo').'/'.$v->path.'thumb/'.$v->photo?>"  class="img-responsive">
                		<div class="c_words">
                		当前价:￥ <?= $v->current_price?>
                		</div>
                		</div>
                		</a>	
					</div>
					
					<?php }?>
					</div>
					
					<?php if(!empty($model->source)){?>
					<p>拍品提供:  <?=$model->source?></p>
					<?php }?>
					</div>			 				 
				</div>			
			
			
			</li>						
			</ul>				 				 
        </div>
   
</a>

  
