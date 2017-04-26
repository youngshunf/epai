<?php

use yii\helpers\Url;
use common\models\LotteryRec;
use common\models\CommonUtil;

?>

<a href="<?= Url::to(['view','id'=>$model['id']])?>">
        <div class="col-md-4">
            <ul class="auction">
			<li class="">
				<a href="<?= Url::to(['view','id'=>$model->id])?>">
					<img src="<?= yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo?>"  class="img-responsive">
				</a>				
				<div class="pai-item pai-content">
				  <p>
				  <?php if($model->status==0){?><span class="mui-badge mui-badge-danger">进行中 </span>
				  <?php }elseif ($model->status==1){?>
				  <span class="mui-badge mui-badge-warning">已结束</span>
				  <?php }elseif($model->status==2){?>
				  <span class="mui-badge mui-badge-success">已揭晓</span>
				  <?php }?>
				   <?= $model->name?></p>		
				   
				   <?php if($model->status==0){?>				 
				 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$model->end_time)?>" >
				 <span class="countdown-text green">距结束</span>&nbsp;&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>          
				 <div class="clear"></div>
                 </div>
					<?php }elseif ($model->status==2){
					    $award=LotteryRec::findOne(['goods_guid'=>$model->goods_guid,'is_award'=>1]);
					    ?>	
					   <ul class="mui-table-view">
				<li class="mui-table-view-cell mui-media">
					<a href="javascript:;">
					<?php if(empty($award->user->img_path)){?>
						<img class="mui-media-object mui-pull-left img-responsive" style="width:60px"  src="<?= yii::getAlias('@avatar')?>/unknown.jpg" >
						<?php }else{?>
						<img class="mui-media-object mui-pull-left img-responsive"  style="width:60px"  src="<?= $award->user->img_path?>" >
						<?php }?>
						<div class="mui-media-body">
							<p><span class="red-normal">获奖者：</span> <?= !empty($award->user->nick)?$award->user->nick:CommonUtil::truncateMobile($award->user->mobile)?> <span></span></p>
							<p>参与了<span class="green"><?= LotteryRec::find()->andWhere(['user_guid'=>$award->user->user_guid,'goods_guid'=>$model->goods_guid])->count()?></span>人次
							<p>揭晓时间:<?= CommonUtil::fomatTime($award->award_time)?></p>
						</div>
					</a>
				</li>
				<li class="mui-table-view-cell">
					幸运号码: <span class="red"><?= $award->lottery_code?></span>
				</li>
		</ul>
					
					<?php }?>
				<div class="progress">
               <div class="progress-bar progress-bar-danger" role="progressbar" 
                  aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" 	
                  style="width: <?= round($model->count_lottery/$model->price*100,2)?>%;">
                  <span class=""><?= round($model->count_lottery/$model->price*100,2)?>%</span>
               </div>
                </div>
                
                <p>
                <span>总需 <i class="green"><?= $model->price?></i> 次数</span>
                  <span class="pull-right">剩余 <i class="red-normal"><?= $model->price-$model->count_lottery?></i> 人次</span>
                </p>
                  <div class="center">	
                  <a href="<?= Url::to(['view','id'=>$model->id])?>"  class="btn btn-success " >查看详情</a>	
                  <?php if($model->status==0){?>
					<a href="<?= Url::to(['buy','id'=>$model->id])?>"  class="btn btn-danger " >立即购买</a>
				 <?php }?>
				 </div>		 				 
				</div>
				    
				 
				<div class="item-lottery-box">
                    <span class="side-num">参与人次<?= $model->count_lottery?></span>
				</div>
			
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
  
