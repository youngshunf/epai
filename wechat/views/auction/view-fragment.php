<?php

use common\models\CommonUtil;

use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
$leftTime=intval( $model->end_time - time());

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

?>


  
  <div class="col-md-6" id="modelHead" data-currentprice="<?= $model->current_price?>" data-deltaprice="<?= $delta_price?>" data-currenttime="<?=$auctionTimes?>" >
  <div class="panel-white">
  <div class="<?php if(  $leftTime>=0 && $leftTime <=60 ) echo 'auction-alert'?>">
    <h5><?=$model->name ?>  </h5>
 	 <?php
 if($model->reverse_price!=0.00){
   if($model->current_price<$model->reverse_price){?>
		<p class="organe center">未达到保留价</p>
	<?php }else{?>
		<p class="organe center">拍品已达到保留价</p>
	<?php }}?>
	
    <?php 
    $now=time();
    if($now>=$model->start_time&&$now<=$model->end_time){?>
       <p>当前价格:<span class="red">￥<?= $model->current_price?></span>
         <?php if($model->reverse_price!=0.00){?>
                 <img alt="保留价" src="../img/baoliujia.png" style="width:24px;display: inline-block">
                 <?php }?>
       
       <?php if(!$hasLove){?>
		<a class="pull-right btn btn-warning" href="<?= Url::to(['auction/goods-love','goodsid'=>$model->id])?>">收藏</a> &nbsp;
		<?php }else{?>
		 <a class="pull-right btn btn-danger" href="<?= Url::to(['auction/goodslove-cancel','goodsid'=>$model->id])?>" > 已收藏</a>
		<?php }?>
       
       </p>
    	 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$model->end_time)?>" >
				 &nbsp;<span class="countdown-text">距结束</span>&nbsp;&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
                 <p>
                
                 </p>
                    <div class="auction-info">
                 <table class="table table-striped">
                 <tr>
                 <td>起拍价格:<span class="red-sm">￥<?= $model->start_price?></span></td>
                 <td>当前加价幅度:<span class="red-sm">￥<?= $delta_price?></span>
                  </td>               
                 </tr>                
                 <tr>
                 <td>围观: <?= $model->count_view?></span></td>
                 <td>收藏: <?= $model->count_collection?></span></td>              
                 </tr>
                 <tr>
                 <td colspan="2"><span>起拍时间:<i class="green"><?= CommonUtil::fomatHours($model->start_time)?></i></span></td>
                 </tr>
                   <tr>
                 <td colspan="2"> <span>结束时间:<i class="green"><?= CommonUtil::fomatHours($model->end_time)?></i></span></td>
                 </tr>
                 <?php if(!empty($model->eval_price) && $model->eval_price!=0.00){?>
                     <tr>
                 <td colspan='2'><span>小火估价:<i class="organe"> ￥<?= $model->eval_price?></i></span></td>
                 </tr>
                 <?php }?>
                 </table>
                 
                 </div>
                  <div class="  center">
                  <?php if(yii::$app->user->identity->status==0){?>
					<span class="red">您已被禁止参与拍卖,请及时购买已经成交的拍品.</span>
					<?php }else{?>
					<a  class="btn btn-lg btn-danger  bid-btn" >出价</a>
					<?php if($model->current_price<$model->reverse_price){?>
					<p class="organe">该拍品有保留价</p>
					<?php }?>
					<?php }?>
				 </div>
				
				 <div class="clear"></div>
                 </div>
    <?php }elseif($now<$model->start_time){?>
     <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$model->start_time)?>" >
     	<?php if($model->status!=3){?>
				 &nbsp;<span class="countdown-text">距开始</span>&nbsp;&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft prev"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>      
             <?php }?>   
             	<?php if(!$hasLove){?>
					 <a class="pull-right btn btn-warning" href="<?= Url::to(['auction/goods-love','goodsid'=>$model->id])?>">收藏</a> &nbsp;
					<?php }else{?>
					  <a class="pull-right btn btn-danger" href="<?= Url::to(['auction/goodslove-cancel','goodsid'=>$model->id])?>" > 已收藏</a>
					<?php }?>     
                 <div class="auction-info">
                 <table>
                 <tr>
                 <td>起拍价格:<span class="red-sm">￥<?= $model->start_price?></span></td>
                 <td>加价幅度:<span class="red-sm">￥<?= $model->delta_price?></span>
                  </td>                
                 </tr>
                 
                 <tr>
                 <td>围观: <?= $model->count_view?></span></td>
                 <td>收藏: <?= $model->count_collection?></span></td>              
                 </tr>
                 
                   <tr>
                 <td colspan="2"><span >起拍时间:<i class="green"><?= CommonUtil::fomatHours($model->start_time)?></i></span></td>
                 </tr>
                     <tr>
                 <td colspan="2"> <span>结束时间:<i class="green"><?= CommonUtil::fomatHours($model->end_time)?></i></span></td>
                 </tr>
                 
                   <?php if(!empty($model->eval_price) && $model->eval_price!=0.00){?>
                     <tr>
                 <td colspan='2'><span>小火估价:<i class="organe"> ￥<?= $model->eval_price?></i></span></td>
                 </tr>
                 <?php }?>
                 
                 </table>
                 
                 </div>
                  
				 <div class="clear"></div>
                 </div>
    <?php }else if( $now>$model->end_time ){?>
                  <p>成交价格:<span class="red">￥<?= $model->current_price?></span>
                   <?php if($model->reverse_price!=0.00){?>
                     <img alt="保留价" src="../img/baoliujia.png" style="width:24px;display: inline-block;">
                     <?php }?>
                  
                 <?php if(!$hasLove){?>
					 <a class="pull-right btn btn-warning" href="<?= Url::to(['auction/goods-love','goodsid'=>$model->id])?>">收藏</a> &nbsp;
					<?php }else{?>
					  <a class="pull-right btn btn-danger" href="<?= Url::to(['auction/goodslove-cancel','goodsid'=>$model->id])?>" > 已收藏</a>
					<?php }?>
                  </p>
                <p class="organe"><?= CommonUtil::getDescByValue('auction_goods','status',$model->status)?></p>
                   <div class="auction-info">
                 <table>
                 <tr>
                 <td>起拍价格:<span class="red-sm">￥<?= $model->start_price?></span></td>
                 <td>加价幅度:<span class="red-sm">￥<?= $model->delta_price?></span>
                 
                 </td>
                 
                 </tr>
                 
                 <tr>
                 <td>围观: <?= $model->count_view?></span></td>
                 <td>收藏: <?= $model->count_collection?></span></td>              
                 </tr>
                 
                   <tr>
                 <td colspan="2"><span>起拍时间:<i class="green"><?= CommonUtil::fomatHours($model->start_time)?></i></span></td>
                 </tr>
                 <tr>
                 <td colspan="2"> <span>结束时间:<i class="green"><?= CommonUtil::fomatHours($model->end_time)?></i></span></td>
                 </tr>
                 <?php if(!empty($model->eval_price) && $model->eval_price!=0.00){?>
                     <tr>
                 <td colspan='2'><span>小火估价:<i class="organe"> ￥<?= $model->eval_price?></i></span></td>
                 </tr>
                 <?php }?>
                 </table>
                 <p class="center">
                 
                 </p>
                 </div>
   
   
    <?php }?>
    
   
    </div>
     <p class="center">
                 <a href="<?= Url::to(['site/auction-rules'])?>">拍卖规则</a>
       </p>
    
    <div class="auction-rec">
    
    <?php if($model->start_time<$now){?>
        <p class="bold">出价记录</p>
        <?php Pjax::begin(['id'=>'bid-rec'])?>
         <?= GridView::widget([
        'dataProvider' => $bidRecData,
        'columns' => [
            ['attribute'=> '状态',
               'format' => 'html',        
             'value'=>function ($model){
             if($model->is_deal==1){
                 return "<span class='auc-leading'>成交</span>";
             }else{
                if($model->is_leading==1){           
                    return "<span class='auc-leading'>领先</span>";                    
                }else{
                     return "<span class='auc-out'>出局</span>";  
                }
             }
            },
            'options'=>['width'=>'65px']
            ],
            ['attribute'=> '竞拍人',
            'format' => 'html',
            'value'=>function ($model){
               return empty($model->user->name)?CommonUtil::truncateMobile($model->user->mobile):$model->user->name;
            }],
             ['attribute'=> '价格',
            'format' => 'html',
             'options'=>['width'=>'100px'],
            'value'=>function ($model){
                $price= "<span class='red-sm'>￥".$model->price."</span>";
               /*  if($model->is_agent){
                    $price.="<span class='green'>(代理)</span>";
                } */
               return $price;
            }],      
           ['attribute'=> '时间','value'=>function ($model){
               return CommonUtil::fomatTime($model->created_at);
           }],     
          
        ],
        'tableOptions'=>['class'=>'table table-striped '],
    ]); ?>
    <?php Pjax::end()?>
    
    <?php }?>
    </div>
    
    </div>
    </div>
    


  