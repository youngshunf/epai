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


  
  <div class="col-md-6">
  <div class="panel-white">
  <div class="<?php if(  $leftTime>=0 && $leftTime <=60 ) echo 'auction-alert'?>">
    <h5><?=$model->name ?>  </h5>
 	 <?php
 if($model->reverse_price!=0.00){
   if($model->current_price<$model->reverse_price){?>
		<p class="red-sm center">未达到保留价</p>
	<?php }else{?>
		<p class="red-sm center">拍品已达到保留价</p>
	<?php }}?>
	
    <?php 
    $now=time();
    if($now>=$model->start_time&&$now<=$model->end_time){?>
       <p>当前价格:<span class="red">￥<?= $model->current_price?></span>
       
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
                  <?php if($model->reverse_price!=0.00){?>
                 <span class="icon-money"></span>
                 <?php }?></td>               
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
                 <td colspan='2'><span>小火估价:<i class="red-sm"> ￥<?= $model->eval_price?></i></span></td>
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
					<p class="red-sm">该拍品有保留价</p>
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
                  <?php if($model->reverse_price!=0.00){?>
                 <span class="icon-money"></span>
                 <?php }?></td>                
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
                 <td colspan='2'><span>小火估价:<i class="red-sm"> ￥<?= $model->eval_price?></i></span></td>
                 </tr>
                 <?php }?>
                 
                 </table>
                 
                 </div>
                  
				 <div class="clear"></div>
                 </div>
    <?php }else if( $now>$model->end_time ){?>
                  <p>成交价格:<span class="red">￥<?= $model->current_price?></span>
                  
                 <?php if(!$hasLove){?>
					 <a class="pull-right btn btn-warning" href="<?= Url::to(['auction/goods-love','goodsid'=>$model->id])?>">收藏</a> &nbsp;
					<?php }else{?>
					  <a class="pull-right btn btn-danger" href="<?= Url::to(['auction/goodslove-cancel','goodsid'=>$model->id])?>" > 已收藏</a>
					<?php }?>
                  </p>
                <p class="red"><?= CommonUtil::getDescByValue('auction_goods','status',$model->status)?></p>
                   <div class="auction-info">
                 <table>
                 <tr>
                 <td>起拍价格:<span class="red-sm">￥<?= $model->start_price?></span></td>
                 <td>加价幅度:<span class="red-sm">￥<?= $model->delta_price?></span>
                  <?php if($model->reverse_price!=0.00){?>
                 <span class="icon-money"></span>
                 <?php }?>
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
                 <td colspan='2'><span>小火估价:<i class="red-sm"> ￥<?= $model->eval_price?></i></span></td>
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
    
    	     <!-- 出价 -->
<div class="modal fade" id="submit-bid" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               出价
            </h4>
         </div>
         <div class="modal-body">
         <?php if(yii::$app->user->isGuest){?>
            <div class="form-group center">
                <p>您还未登录,请先
                <a class="btn btn-success" href="<?= Url::to(['site/login'])?>">登录</a>
                <a class="btn btn-primary" href="<?= Url::to(['site/register'])?>">注册</a>
                </p>
            </div>
         <?php }else{?>
            	<form action="<?= Url::to(['submit-bid'])?>" id="bid-form" method="post" onsubmit="return check()">
            	<p>当前价格:<span class="red">￥<?= $model->current_price?></span></p>
            	<p>当前加价幅度:<span class="red">￥<?= $delta_price?></span></p>
            	<div class="form-group required" >
            	<label class="label-control">竞拍价格:</label>
            	<input type="text" name="bid-price" id="bid-price" class="form-control" value="<?= $model->current_price+$delta_price?>">
            	<span class="red-sm hide">*竞拍价格不能低于当前价格,且为加价幅度的整数倍</span>
            	<?php if($model->current_price<$model->reverse_price){?>
					<p class="red-sm center">该拍品有保留价</p>
					<?php }?>
            	</div>
            	<input type="hidden" name="goods-guid"  value="<?= $model->goods_guid?>">
             <div class="form-group center">
            	<button type="button" class="btn btn-success " id="bidSubmit">提交</button>
            	</div>
            	</form>
            	<?php }?>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default"  id="modal-close"
               data-dismiss="modal">关闭
            </button>
         
         </div>
      </div><!-- /.modal-content -->
</div>
</div><!-- /.modal -->
  