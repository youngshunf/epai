<?php

use yii\web\View;
use yii\widgets\LinkPager;
use common\models\CommonUtil;
use yii\helpers\Url;

?>

<a href="<?= Url::to(['view','id'=>$model['id']])?>">
        <div class="col-md-3">
            <ul class="auction">
			<li class="pai-item">
				<a href="<?= Url::to(['view','id'=>$model->id])?>">
					<img src="<?= yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo?>"  class="img-responsive">
				</a>	
				<?php 
				$now=time();
				if($now>=$model->start_time&&$now<=$model->end_time){
				?>			
				<div class="pai-content">
				 <h3 class="ellipsis"><?= $model->name?></h3>
				 <p>起拍价格:<i class="red-sm">￥<?= $model->start_price?></i> <span class="pull-right"> 当前价格:<i class="red">￥<?= $model->current_price?></i></span></p>				 
				 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$model->end_time)?>" >
				 &nbsp;<span class="countdown-text">距结束</span>&nbsp;&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
                  <div class="item-button">
					<a href="<?= Url::to(['view','id'=>$model->id])?>" class="btn btn-default">围观(<?= $model->count_view?>)</a>  &nbsp;
					<a href="<?= Url::to(['view','id'=>$model->id])?>"  class="btn btn-danger bid-btn" >出价</a>
				 </div>
				 <div class="clear"></div>
                 </div>
								 				 
				</div>
				<div class="item-bid-box">
                    <span class="side-num"><?= $model->count_auction?></span>次出价
				</div>
			<?php }elseif ($now<$model->start_time){?>
			     	<div class="pai-content">
				 <h3 class="ellipsis"><?= $model->name?></h3>
				 <p>起拍价格:<i class="red-sm">￥<?= $model->start_price?></i> <span class="pull-right">加价幅度:<i class="red">￥<?= $model->delta_price?></i></span></p>				 
				 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$model->start_time)?>">
				 &nbsp;<span class="countdown-text">距开始</span>&nbsp;&nbsp;
				 <p class=" pai-countdown"  >
                        <span class="J_TimeLeft prev"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
                  <div class="item-button">
					<a href="<?= Url::to(['view','id'=>$model->id])?>" class="btn btn-default">围观(<?= $model->count_view?>)</a>  &nbsp;
					<a href="<?= Url::to(['view','id'=>$model->id])?>" class="btn btn-success prev-btn">查看</a>
				 </div>
				 <div class="clear"></div>
                 </div>								 
				</div>			
			<?php }elseif ($now>$model->end_time){?>
				<div class="pai-content">
				 <h3 class="ellipsis"><?= $model->name?></h3>
				 <p>起拍价格:<i class="red-sm">￥<?= $model->start_price?></i> <span class="pull-right"> 当前价格:<i class="red">￥<?= $model->current_price?></i></span></p>				 
				 &nbsp;<span class="red">已结束</span>&nbsp;&nbsp;
                  <div class="item-button">
					<a href="<?= Url::to(['view','id'=>$model->id])?>" class="btn btn-default">围观(<?= $model->count_view?>)</a>  &nbsp;
				 </div>
				 <div class="clear"></div>
                 </div>
				<div class="item-bid-box">
                    <span class="side-num"><?= $model->count_auction?></span>次出价
				</div>
			<?php }?>
			</li>						
			</ul>				 				 
        </div>
</a>
<script type="text/javascript">

    $(".item-countdown").each(function(){
        var that=$(this);
        var countTime=$(this).attr('data-time');
        $(this).downCount({
    		date: countTime,
    		offset: +10
    	}, function () {
    	//	alert('倒计时结束!');
        	that.find('.bid-btn').removeClass('btn-danger');
        	that.find('.bid-btn').html('已结束');
    	});
    });    	
    

</script>
  
