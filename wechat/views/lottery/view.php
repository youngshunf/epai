<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\helpers\Url;
use common\models\LotteryRec;

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '一元夺宝', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
a{
	color:#000;
}
</style>
    <div class="col-md-6">
   <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo?>" class="img-responsive">
  </div>


    <div class="row">

  <div class="col-md-6">
    <div class="panel-white">
    <p> <?php if($model->status==0){?><span class="mui-badge mui-badge-danger">进行中 </span>
				  <?php }elseif ($model->status==1){?>
				  <span class="mui-badge mui-badge-warning">已结束</span>
				  <?php }elseif($model->status==2){?>
				  <span class="mui-badge mui-badge-success">已揭晓</span>
				  <?php }?>
   <?= $model->name?></p>
    <div class="progress">
   <div class="progress-bar progress-bar-danger" role="progressbar" 
      aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" 	
      style="width: <?= round($model->count_lottery/$model->price*100,2)?>%;">
      <span><?= round($model->count_lottery/$model->price*100,2)?>%</span>
   </div>
    </div>
    
    <p>
    <span>总需 <i class="green"><?= $model->price?></i> 次数</span>
      <span class="pull-right">剩余 <i class="red-normal"><?= $model->price-$model->count_lottery?></i> 人次</span>
    </p>
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
						<img class="mui-media-object mui-pull-left"  src="<?= yii::getAlias('@avatar')?>/unknown.jpg" >
						<?php }else{?>
						<img class="mui-media-object mui-pull-left"  src="<?= $award->user->img_path?>" >
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
    </div>
    
    <div class="col-xs-12">   
          <a href="<?= Url::to(['lottery-rec','goods_guid'=>$model->goods_guid])?>" >  
       <div class="panel-white">
     <span class="icon-list"> </span> 参与记录      
          </div>
          </a>
     </div>
     <div class="col-xs-12">   
       <div class="panel-white">
       <h5>商品介绍</h5>
      <?= $model->desc?>
          </div>
  </div>
</div>
</div>

<?php if($model->status==0){?>	
<div class="bottom-button">
<a class="btn btn-danger btn-block buy-btn"  href="<?= Url::to(['buy','id'=>$model->id])?>">立即购买</a>
</div>
<?php }?>
<script type="text/javascript">

    $(".item-countdown").each(function(){
        var that=$(this);
        var countTime=$(this).attr('data-time');
        $(this).downCount({
    		date: countTime,
    		offset: +10
    	}, function () {
    	//	alert('倒计时结束!');
        	that.find('.buy-btn').removeClass('btn-danger');
        	that.find('.buy-btn').html('已结束');
    	});
    });    	
    

</script>
