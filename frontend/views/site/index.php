<?php

use yii\web\View;
use yii\widgets\LinkPager;
use common\models\CommonUtil;
use yii\widgets\ListView;
use yii\helpers\Url;
use common\models\AuctionGoods;
$this->title = '易拍宝-专业的网络拍卖平台';
$this->registerJsFile('@web/js/swiper.min.js', ['position'=> View::POS_HEAD]);
$this->registerCssFile('@web/css/swiper.min.css');
$this->registerCssFile('@web/css/basic.swiper.css');
$this->registerJsFile('@web/nivo-slider/jquery.nivo.slider.pack.js');
$this->registerCssFile('@web/nivo-slider/themes/default/default.css');
$this->registerCssFile('@web/nivo-slider/nivo-slider.css');
?>
<style>
.media-object{
	width:100px;
}
#round .row {
	margin:0 !important;
	  padding-bottom: 15px;
}
#round  .col-sm-6,#round  .col-md-6,#round  .col-lg-6{
  padding-right: 0px !important;
  padding-left: 0px !important;
}
#round  .auction {
	min-height:325px;
}
#round  .col-xs-6{
  padding-right: 5px !important;
  padding-left: 5px !important;
 	  padding-bottom: 5px;
}
</style>
       <div class="swiper-container swiper1">
        <div class="swiper-wrapper">
        <?php foreach ($homePhoto as $photo){?>
            <div class="swiper-slide">
            <?php if($photo->type==0){?>
             <a href="<?= $photo->url?>" target="_blank">
             <?php }else{?>
             <a href="<?= Url::to(['site/home-view','id'=>$photo->id])?>" target="_blank">
             <?php }?>
            <img alt="" src="<?= yii::getAlias('@photo').'/'.$photo->path.$photo->photo?>" class="img-responsive" />
            </a>
            </div>
          <?php }?>
          
       
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Arrows -->
       <div class="swiper-button-next hidden-xs"></div>
        <div class="swiper-button-prev hidden-xs" ></div> 
      </div>
        
    
    <div class="wrap news-bar">
     <div class="container">
        <div class="row">
        <div class="col-md-4">
            <div class="sharp-title-small bg-blue border-blue">
	              <font>新闻资讯</font>
	              <div class="triangle-down"></div>
	              <div class="title-font"><span> / </span> NEWS</div>
	               <a class="pull-right title-more-blue" href="<?= Url::to(['news/index'])?>">更多&raquo;</a>
	               <div class="clear"></div>
				 </div>
				 
				 <div class="slider-wrapper theme-default">
                 <div id="slider" class="nivoSlider">
				 <?php foreach ($news as $k=> $new){
				     if($k>3) break;
				     ?>
				     
					 <a href="<?= Url::to(['news/view','id'=>$new->newsid])?>">
				 <img alt="<?= $new->title?>" title="<?= $new->title?>" src="<?= yii::getAlias('@photo').'/'.$new->path.'mobile/'.$new->photo?>" class="img-responsive" >			  
			     	 </a>
				
				 <?php }?>
				 </div>
				 </div>
				 
				 <div class="content-padded">
				 <?php foreach ($news as $k=>$new){
				    if($k<=3) continue;
				     ?>
				 <p class="news-list"><a class="ellipsis" href="<?= Url::to(['news/view','id'=>$new->newsid])?>"><?= $new->title?></a></p>				 
				 <?php }?>
				 </div>
        </div>
        
        
          <div class="col-md-4">
            <div class="sharp-title-small bg-blue border-blue ">
	              <font>易宝天下</font>
	              <div class="triangle-down"></div>
	              <div class="title-font"><span> / </span> TREASURE</div>
	               <a class="pull-right title-more-blue" href="<?= Url::to(['treasure/index'])?>">更多&raquo;</a>
	               <div class="clear"></div>
				 </div>
				 
				 <?php foreach ($treasures as $k=>$v){?>
				 <?php if($k==0){?>
        		<div class="media">
                   <a class="pull-left" href="<?= Url::to(['treasure/view','id'=>$v->newsid])?>">
                      <img class="media-object " src="<?= yii::getAlias('@photo').'/'.$v->path.'thumb/'.$v->photo?>" 
                      alt="<?= $v->title?>">
                   </a>
                   <div class="media-body">
                    <a  href="<?= Url::to(['treasure/view','id'=>$v->newsid])?>">  <h4 class="media-heading"><?= $v->title?></h4></a>
                     <p class="indent"><?=  CommonUtil::cutHtml($v->content,50)?></p>
                	  </div>
                	 </div>     
        	 <?php }elseif ($k==1){?>
        	 
        	 	<div class="media">            
                   <div class="media-body">
                      <h4 class="media-heading"><a class="ellipsis" href="<?= Url::to(['treasure/view','id'=>$v->newsid])?>"><?= $v->title?></a></h4>
                     <p class="indent"><?=  CommonUtil::cutHtml($v->content,50)?></p>
                	  </div>
                	 </div>   
        	 <?php }else{?>
        	   	 <p class="news-list"><a class="ellipsis" href="<?= Url::to(['treasure/view','id'=>$v->newsid])?>"><?= $v->title?></a></p>
        	   	 
        	 <?php } }?>
				 
        </div>
        
          <div class="col-md-4">
            <div class="sharp-title-small bg-blue border-blue">
	              <font>知文玩</font>
	              <div class="triangle-down"></div>
	              <div class="title-font"><span> / </span> KNOWLEDGE</div>
	               <a class="pull-right title-more-blue" href="<?= Url::to(['knowledge/index'])?>">更多&raquo;</a>
	               <div class="clear"></div>
	              
				 </div>
				 
				  <?php foreach ($knowledges as $k=>$v){?>
				 <?php if($k==0){?>
        		<div class="media">
                   <a class="pull-left" href="<?= Url::to(['knowledge/view','id'=>$v->newsid])?>">
                      <img class="media-object " src="<?= yii::getAlias('@photo').'/'.$v->path.'thumb/'.$v->photo?>" 
                      alt="<?= $v->title?>">
                   </a>
                   <div class="media-body">
                    <a  href="<?= Url::to(['knowledge/view','id'=>$v->newsid])?>">  <h4 class="media-heading"><?= $v->title?></h4></a>
                     <p class="indent"><?=  CommonUtil::cutHtml($v->content,50)?></p>
                	  </div>
                	 </div>     
        	 <?php }elseif ($k==1){?>
        	 
        	 	<div class="media">            
                   <div class="media-body">
                      <h4 class="media-heading"><a class="ellipsis" href="<?= Url::to(['knowledge/view','id'=>$v->newsid])?>"><?= $v->title?></a></h4>
                     <p class="indent"><?=  CommonUtil::cutHtml($v->content,50)?></p>
                	  </div>
                	 </div>   
        	 <?php }else{?>
        	   	 <p class="news-list"><a class="ellipsis" href="<?= Url::to(['knowledge/view','id'=>$v->newsid])?>"><?= $v->title?></a></p>
        	   	 
        	 <?php } }?>
				 
        </div>
        
        </div>
     </div>    
     </div>
     
      <div class="wrap aucation-bar" id="round">
     <div class="container">
        <div class="row">
        <div class="sharp-title-small bg-red border-red">
	              <font>专场拍卖</font>
	              <div class="triangle-downred"></div>
	              <div class="title-fontred"><span> / </span> ROUND</div>
	                  <a class="pull-right title-more-red" href="<?= Url::to(['auction/round'])?>">更多&raquo;</a>
	              
	               <div class="clear"></div>
		</div>
        
        <?php foreach ($round as $k=>$v){
        $count=count($round);
            ?>
        <?php if($count%2==0){?>
         <div class="row">
    <div class="col-md-6">
    <a href="<?= Url::to(['auction/round-view','id'=>$v->id])?>">
		<img src="<?= yii::getAlias('@photo').'/'.$v->path.'mobile/'.$v->photo?>"  class="img-responsive">
		</a>	
    </div>
        <div class="col-md-6">
            <ul class="auction">
			<li class="pai-item">
				<div class="pai-content">
				 <h5 class="ellipsis"><?= $v->name?>
			
				 </h5>
				  
				  <?php
                    $now=time();
                 if($now>=$v->start_time&&$now<=$v->end_time){?>
                     <p>	 <span >共<i class="red"><?= AuctionGoods::find()->andWhere(['roundid'=>$v->id])->count()?></i>个拍品, <span > <i class="red"><?=AuctionGoods::find()->andWhere(['roundid'=>$v->id])->sum(' count_auction ') ?></i>次竞拍</span>	</span><a href="<?= Url::to(['auction/round-view','id'=>$v->id])?>" class="btn btn-danger  pull-right">竞拍中</a></p>
             <p class="clear"></p>
				 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$v->end_time)?>" >
				 &nbsp;<span class="countdown-text">距结束</span>&nbsp;&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
				 <div class="clear"></div>
                 </div>
                 <?php }elseif ($now>$v->end_time){?>
                  <div  >
                  	 <span >共<i class="red"><?= AuctionGoods::find()->andWhere(['roundid'=>$v->id])->count()?></i>个拍品, <span > <i class="red"><?=AuctionGoods::find()->andWhere(['roundid'=>$v->id])->sum(' count_auction ') ?></i>次竞拍</span>	</span>
        				 &nbsp;<a href="<?= Url::to(['auction/round-view','id'=>$v->id])?>"  class="btn btn-default  pull-right">已结束</a>&nbsp;&nbsp;
        				 <div class="clear"></div>
                    </div>
          
          <?php }elseif ($now<$v->start_time){?>
             <p>	 <span >共<i class="red"><?= AuctionGoods::find()->andWhere(['roundid'=>$v->id])->count()?></i>个拍品, <span > <i class="red"><?=AuctionGoods::find()->andWhere(['roundid'=>$v->id])->sum(' count_auction ') ?></i>次竞拍</span>	</span><a href="<?= Url::to(['auction/round-view','id'=>$v->id])?>" class="btn btn-success  pull-right">即将开始</a></p>
             <p class="clear"></p>
          	 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$v->start_time)?>" >
				 &nbsp;<span class="countdown-text">距开始</span>&nbsp;&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
             
				 <div class="clear"></div>
                 </div>
          
          <?php }?>
                 <br>
					<div class="row">
					<?php $roundGoods=AuctionGoods::find()->andWhere(['roundid'=>$v->id])->limit(4)->all();
					foreach ($roundGoods as $key=>$item){
					?>
					<div class="col-md-3 col-xs-6">
					    <a href="<?= Url::to(['auction/view','id'=>$item->id])?>">
					    <div class="c_img">
                		<img src="<?= yii::getAlias('@photo').'/'.$item->path.'thumb/'.$item->photo?>"  class="img-responsive">
                		<div class="c_words">
                		当前价:￥ <?= $item->current_price?>
                		</div>
                		</div>
                		</a>	
					</div>
					
					<?php }?>
					</div>
					
					<?php if(!empty($v->source)){?>
					<p>拍品提供:  <?=$v->source?></p>
					<?php }?>
					</div>			 				 
			
			</li>						
			</ul>				 				 
        </div>
        </div>
        <?php }else {?>
        <?php if($k==0){?>
         <div class="row">
    <div class="col-md-6">
    <a href="<?= Url::to(['auction/round-view','id'=>$v->id])?>">
		<img src="<?= yii::getAlias('@photo').'/'.$v->path.'mobile/'.$v->photo?>"  class="img-responsive">
		</a>	
    </div>
        <div class="col-md-6">
            <ul class="auction">
			<li class="pai-item">
				<div class="pai-content">
				 <h5 class="ellipsis"><?= $v->name?>
			
				 </h5>
				  
				  <?php
                    $now=time();
                 if($now>=$v->start_time&&$now<=$v->end_time){?>
                     <p>	 <span >共<i class="red"><?= AuctionGoods::find()->andWhere(['roundid'=>$v->id])->count()?></i>个拍品, <span > <i class="red"><?=AuctionGoods::find()->andWhere(['roundid'=>$v->id])->sum(' count_auction ') ?></i>次竞拍</span>	</span><a href="<?= Url::to(['auction/round-view','id'=>$v->id])?>" class="btn btn-danger  pull-right">竞拍中</a></p>
             <p class="clear"></p>
				 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$v->end_time)?>" >
				 &nbsp;<span class="countdown-text">距结束</span>&nbsp;&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
				 <div class="clear"></div>
                 </div>
                 <?php }elseif ($now>$v->end_time){?>
                  <div  >
                  	 <span >共<i class="red"><?= AuctionGoods::find()->andWhere(['roundid'=>$v->id])->count()?></i>个拍品, <span > <i class="red"><?=AuctionGoods::find()->andWhere(['roundid'=>$v->id])->sum(' count_auction ') ?></i>次竞拍</span>	</span>
        				 &nbsp;<a href="<?= Url::to(['auction/round-view','id'=>$v->id])?>"  class="btn btn-default  pull-right">已结束</a>&nbsp;&nbsp;
        				 <div class="clear"></div>
                    </div>
          
          <?php }elseif ($now<$v->start_time){?>
             <p>	 <span >共<i class="red"><?= AuctionGoods::find()->andWhere(['roundid'=>$v->id])->count()?></i>个拍品, <span > <i class="red"><?=AuctionGoods::find()->andWhere(['roundid'=>$v->id])->sum(' count_auction ') ?></i>次竞拍</span>	</span><a href="<?= Url::to(['auction/round-view','id'=>$v->id])?>" class="btn btn-success  pull-right">即将开始</a></p>
             <p class="clear"></p>
          	 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$v->start_time)?>" >
				 &nbsp;<span class="countdown-text">距开始</span>&nbsp;&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
             
				 <div class="clear"></div>
                 </div>
          
          <?php }?>
                 <br>
					<div class="row">
					<?php $roundGoods=AuctionGoods::find()->andWhere(['roundid'=>$v->id])->limit(4)->all();
					foreach ($roundGoods as $key=>$item){
					?>
					<div class="col-md-3 col-xs-6">
					    <a href="<?= Url::to(['auction/view','id'=>$item->id])?>">
					    <div class="c_img">
                		<img src="<?= yii::getAlias('@photo').'/'.$item->path.'thumb/'.$item->photo?>"  class="img-responsive">
                		<div class="c_words">
                		当前价:￥ <?= $item->current_price?>
                		</div>
                		</div>
                		</a>	
					</div>
					
					<?php }?>
					</div>
					
					<?php if(!empty($v->source)){?>
					<p>拍品提供:  <?=$v->source?></p>
					<?php }?>
					</div>			 				 
			
			</li>						
			</ul>				 				 
        </div>
        </div>
        <?php }else{?>
        <div class="col-md-6">
         <div class="row">
    <div class="col-md-6">
    <a href="<?= Url::to(['auction/round-view','id'=>$v->id])?>">
		<img src="<?= yii::getAlias('@photo').'/'.$v->path.'mobile/'.$v->photo?>"  class="img-responsive">
		</a>	
    </div>
        <div class="col-md-6" style="padding-right: 10px !important">
            <ul class="auction" style="min-height: 165px">
			<li class="pai-item">
				<div class="pai-content">
				 <h5 class="ellipsis"><?= $v->name?>
			
				 </h5>
				  
				  <?php
                    $now=time();
                 if($now>=$v->start_time&&$now<=$v->end_time){?>
                     <p>	 <span >共<i class="red"><?= AuctionGoods::find()->andWhere(['roundid'=>$v->id])->count()?></i>个拍品, <span > <i class="red"><?=AuctionGoods::find()->andWhere(['roundid'=>$v->id])->sum(' count_auction ') ?></i>次竞拍</span>	</span><a href="<?= Url::to(['auction/round-view','id'=>$v->id])?>" class="btn btn-danger  pull-right">竞拍中</a></p>
             <p class="clear"></p>
				 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$v->end_time)?>" >
				 &nbsp;<span class="countdown-text">距结束</span>&nbsp;&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
				 <div class="clear"></div>
                 </div>
                 <?php }elseif ($now>$v->end_time){?>
                  <div  >
                  	 <span >共<i class="red"><?= AuctionGoods::find()->andWhere(['roundid'=>$v->id])->count()?></i>个拍品, <span > <i class="red"><?=AuctionGoods::find()->andWhere(['roundid'=>$v->id])->sum(' count_auction ') ?></i>次竞拍</span>	</span>
        				 &nbsp;<a href="<?= Url::to(['auction/round-view','id'=>$v->id])?>"  class="btn btn-default  pull-right">已结束</a>&nbsp;&nbsp;
        				 <div class="clear"></div>
                    </div>
          
          <?php }elseif ($now<$v->start_time){?>
             <p>	 <span >共<i class="red"><?= AuctionGoods::find()->andWhere(['roundid'=>$v->id])->count()?></i>个拍品, <span > <i class="red"><?=AuctionGoods::find()->andWhere(['roundid'=>$v->id])->sum(' count_auction ') ?></i>次竞拍</span>	</span><a href="<?= Url::to(['auction/round-view','id'=>$v->id])?>" class="btn btn-success  pull-right">即将开始</a></p>
             <p class="clear"></p>
          	 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$v->start_time)?>" >
				 &nbsp;<span class="countdown-text">距开始</span>&nbsp;&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
             
				 <div class="clear"></div>
                 </div>
          
          <?php }?>
                 <br>
				
					
					<?php if(!empty($v->source)){?>
					<p>拍品提供:  <?=$v->source?></p>
					<?php }?>
					</div>			 				 
			
			</li>						
			</ul>				 				 
        </div>
        </div>
        
        </div>
        
            <?php }?>
        <?php }?>
        
        
        <?php }?>
        
        </div>
     </div>    
     </div>
     
     
     <div class="wrap news-bar">
     <div class="container">
        <div class="row">
        <div class="sharp-title-small bg-red border-red">
	              <font>天天易拍</font>
	              <div class="triangle-downred"></div>
	              <div class="title-fontred"><span> / </span> AUCATION</div>
	                  <a class="pull-right title-more-red" href="<?= Url::to(['auction/index'])?>">更多&raquo;</a>
	              
	               <div class="clear"></div>
		</div>
        
        <?php foreach ($auction as $v){?>
        <div class="col-md-3">
            <ul class="auction">
			<li class="pai-item">
				<a href="<?= Url::to(['auction/view','id'=>$v->id])?>">
					<img src="<?= yii::getAlias('@photo').'/'.$v->path.'mobile/'.$v->photo?>"  class="img-responsive">
				</a>				
				<div class="pai-content">
				 <h3 class="ellipsis"><?= $v->name?></h3>
				 <p>起拍价格:<i class="red">￥<?= $v->start_price?></i> <span class="pull-right"> 当前价格:<i class="red">￥<?= $v->current_price?></i></span></p>				 
				 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$v->end_time)?>" >
				 &nbsp;<span class="countdown-text">距结束</span>&nbsp;&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
                  <div class="item-button">
					<a href="<?= Url::to(['auction/view','id'=>$v->id])?>" class="btn btn-default">围观(<?= $v->count_view?>)</a>  &nbsp;
					<a href="<?= Url::to(['auction/view','id'=>$v->id])?>"class="btn btn-danger bid-btn">出价</a>
				 </div>
				 <div class="clear"></div>
                 </div>
				
				 
				 
				</div>
				
				<div class="item-bid-box">
                    <span class="side-num"><?= $v->count_auction?></span>次出价
				</div>
			
			</li>						
			</ul>				 				 
        </div>
        <?php }?>
        
        </div>
     </div>    
     </div>
     
        <div class="wrap aucation-bar">
     <div class="container">
        <div class="row">
        <div class="sharp-title-small bg-red border-red">
	              <font>拍品预展</font>
	              <div class="triangle-downred"></div>
	              <div class="title-fontred"><span> / </span> PREVIEW</div>
	                  <a class="pull-right title-more-red" href="<?= Url::to(['auction/index'])?>">更多&raquo;</a>
	              
	               <div class="clear"></div>
		</div>
        
           <?php foreach ($preview as $v){?>
        <div class="col-md-3">
            <ul class="auction">
			<li class="pai-item">
				<a href="<?= Url::to(['auction/view','id'=>$v->id])?>">
					<img src="<?= yii::getAlias('@photo').'/'.$v->path.'mobile/'.$v->photo?>"  class="img-responsive">
				</a>				
				<div class="pai-content">
				 <h3 class="ellipsis"><?= $v->name?></h3>
				 <p>起拍价格:<i class="red">￥<?= $v->start_price?></i> <span class="pull-right">加价幅度:<i class="red">￥<?= $v->delta_price?></i></span></p>				 
				 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$v->start_time)?>">
				 &nbsp;<span class="countdown-text">距开始</span>&nbsp;&nbsp;
				 <p class=" pai-countdown"  >
                        <span class="J_TimeLeft prev"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
                  <div class="item-button">
					<a href="<?= Url::to(['auction/view','id'=>$v->id])?>" class="btn btn-default">围观(<?= $v->count_view?>)</a>  &nbsp;
					<a href="<?= Url::to(['auction/view','id'=>$v->id])?>" class="btn btn-success prev-btn">查看</a>
				 </div>
				 <div class="clear"></div>
                 </div>								 
				 
				</div>
				<?php if($v->status==3){?>
				<div class="item-bid-box">
                    <span class="side-num">已售出</span>
				</div>
			 <?php }?>
			
			</li>						
			</ul>				 				 
        </div>
        <?php }?>
            
				 
				 
        </div>
        
        
        </div>
     </div>    

     
         <script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
       nextButton: '.swiper-button-next',
       prevButton: '.swiper-button-prev',
        slidesPerView: 1,
        paginationClickable: true,
        spaceBetween: 0,
        loop: true,
        autoplay : 3000,
   
    });
    
    

    $(document).ready(function(){
    	$('#slider').nivoSlider();
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
            	that.find('.prev-btn').removeClass('btn-success');
            	that.find('.prev-btn').addClass('btn-danger');
            	that.find('.prev-btn').html('出价');
        	});
        });    	
        
    });
    </script>   