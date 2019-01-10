<?php

use common\models\CommonUtil;

use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\web\View;
use common\models\Address;

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

$this->title = $model->name;
$leftTime=intval( $model->end_time -time() );
$this->registerJsFile('@web/js/PCASClass.js',['position'=> View::POS_HEAD]);
$this->registerJsFile('@web/js/scale.js');
$this->registerCssFile('@web/css/scale.css');
?>
<style>
.imgzoom_pack{
	background:#000;
}
#goods-desc img{
	max-width:100%;
}
</style>
<div>
<div class="img-wraper list">
<img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.$model->photo?>" class="img-responsive">

</div>

    <div class="row" id="refreshContainer">
  
  <div class="col-md-6" id="modelHead" data-currentprice="<?= $model->current_price?>" data-deltaprice="<?= $delta_price?>" data-currenttime="<?=$auctionTimes?>" >
  <div class="panel-white">
  <div class="<?php if(  $leftTime>=0 && $leftTime<=60 ) echo 'auction-alert'?>">
    <h5><?=$model->name ?> </h5>
 <?php
 if($model->reverse_price!=0.00){
   if($model->current_price<$model->reverse_price){?>
		<p class="organe center">未达到保留价</p>
	<?php }else{?>
<!-- 		<p class="organe center">拍品已达到保留价</p> -->
	<?php }}?>

    <?php 
    $now=time();
    if($now>=$model->start_time&&$now<=$model->end_time){?>
       <p>当前价格:<span class="red">￥<?= $model->current_price?></span>
       <?php if(!empty($agentBid)&& $agentBid->user_guid==yii::$app->user->identity->user_guid){?>
       <p>您当前最高代理价:<span class="red">￥<?= $agentBid->top_price?></span>
       <?php }?>
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
					<button  class="btn  btn-danger  bid-btn"  >出价</button>
					<button  class="btn  btn-success  agent-bid-btn" >代理出价</button>
					<?php if($model->current_price<$model->reverse_price){?>
					<p  style="margin-top:15px"><span class="organe">该拍品有保留价</span></p>
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
                 <td>加价幅度:<span class="red-sm">￥<?= $delta_price?></span>
                 
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
                 <img alt="保留价" src="../img/baoliujia.png" style="width:24px;display: inline-block">
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
                 <td>加价幅度:<span class="red-sm">￥<?= $delta_price?></span>
                  
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
    
    <?php if(!yii::$app->user->isGuest){
    if(yii::$app->user->identity->user_guid==$model->deal_user&&$model->status==2){
        ?>
     <ul class="list-group">
    <?php $address=Address::findOne(['user_guid'=>yii::$app->user->identity->user_guid,'is_default'=>1]);
        if(!empty($address)){?>
   <li class="list-group-item">收货地址:
   <?= $address->province?>   <?= $address->city?>   <?= $address->district?>   <?= $address->address?>   <?= $address->company?>   <?= $address->name?>   <?= $address->phone?>
    
   </li>
   <?php }?>
   <li class="list-group-item" id="newAddress"><span class="glyphicon glyphicon-plus" style="color: rgb(255, 140, 60);"></span>新增收货地址</li>
   </ul>
   <div class="center">
     <a class="btn btn-danger" href="<?= Url::to(['buy-auction','id'=>$model->id])?>">立即购买</a>
    </div>
    <?php } }?>
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
                if(yii::$app->user->identity->user_guid==$model->user->user_guid){
                    return '您本人';
                }
                if(!empty($model->rand_name)){
                    return $model->rand_name;
                }
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
   


   
   
</div>
<div class="row">
  <div class="col-lg-12">
     <div class="panel-white">
  
   <?php if(!empty($model->video_url)){?>
   <div class="center">
     <video src="<?= $model->video_url?>" poster="<?= $model->poster_url?>" controls height="250px" width="100%" preload style="background:#000;max-width:100%"></video>
    </div>
    <?php }?>
    
    <div id="goods-desc">
   
       <?= $model->desc?>
    </div>

  </div>
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
            	<p>当前价格:<span class="red">￥ <span class="current-price"><?= $model->current_price?> </span></span></p>
            	<p>当前加价幅度:<span class="red">￥ <span class="delta-price"><?= $delta_price?></span> </span></p>
            	<div class="form-group required" >
            	<label class="label-control">下一手最低出价:</label>
            	<input type="text" name="bid-price" id="bid-price" class="form-control" value="<?= $model->current_price+$delta_price?>">
            	<span class="red-sm hide">*竞拍价格不能低于当前价格,且为加价幅度的整数倍</span>
            	<?php if($model->current_price<$model->reverse_price){?>
					<p class="organe center" style="margin-top:10px">该拍品有保留价</p>
					<?php }?>
            	</div>
            	<input type="hidden" name="goods-guid"  value="<?= $model->goods_guid?>">
             <div class="form-group center">
            	<button type="button" class="btn btn-success " id="bidSubmit">提交</button>
            	</div>
            	</form>
            	<?php }?>
         </div>
         
      </div><!-- /.modal-content -->
</div>
</div><!-- /.modal -->

<div class="modal fade" id="confirmBidModal" tabindex="1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               提示
            </h4>
         </div>
         <div class="modal-body" >
			<p>请确认您的出价<span class="red">￥<span id="bid-price-final" ></span> </span>,您的出价具有法律效力</p>          
         <div class="modal-footer center">
            <button type="button" class="btn btn-default"  id="modal-close"
               data-dismiss="modal">取消
            </button>
            <button type="button" class="btn btn-success"  id="final-submit"> 确定
            </button>
         
         </div>
      </div><!-- /.modal-content -->
</div>
</div><!-- /.modal -->
</div>

     <!-- 代理出价-->
<div class="modal fade" id="submit-agent" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               代理出价
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
            	<form action="<?= Url::to(['submit-agent'])?>" id="agent-bid-form"  method="post" onsubmit="return checkAgent()">
            	<p>当前价格:<span class="red">￥ <span class="current-price"><?= $model->current_price?> </span></span></p>
            	<p>当前加价幅度:<span class="red">￥ <span class="delta-price"><?= $delta_price?></span> </span></p>
            	
            	<div class="form-group required" >
            	<label class="label-control">最高价格:</label>
            	<input type="text" name="agent-price" id="agent-bid-price" class="form-control">            
            	</div>
            	<input type="hidden" name="goods-guid"  value="<?= $model->goods_guid?>">
             <div class="form-group center">
            	<button type="button" class="btn btn-success " id="agent-bid-btn">提交</button>
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
   


<!-- 收货地址-->
<div class="modal fade" id="AddressModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               收货地址
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
         <?php }else{ ?>
       
            <form action="<?= Url::to(['new-address'])?>" method="post" onsubmit="return checkAddress()">
            	<div class="form-group required" >
            	<label class="label-control">省:</label>
            	<select name="province" id="province" >    </select>
            	<label class="label-control">市:</label>
            	<select  name="city" id="city" >       </select>
            	<label class="label-control">区/县:</label>
            	<select  name="district" id="district" >       </select> 
            	</div>
            	<script type="text/javascript">
            	new PCAS("province","city","district");
            	</script>
            	<div class="form-group required" >
            	<label class="label-control">详细地址:</label>
            	<input type="text" name="address" id="address"  class="form-control">  
            	</div>
            	<div class="form-group required" >
            	<label class="label-control">收件人:</label>
            	<input type="text" name="name" id="name"  class="form-control">  
            	</div>
            	<div class="form-group required" >
            	<label class="label-control">联系电话:</label>
            	<input type="text" name="mobile" id="mobile"  value="<?= yii::$app->user->identity->mobile?>"  class="form-control">  
            	</div>
            	<div class="form-group required" >
            	<label class="label-control">收件单位(选填):</label>
            	<input type="text" name="company" id="company"  class="form-control">  
            	</div>
             <div class="form-group center">
            	<button type="submit" class="btn btn-success ">提交</button>
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


<!-- 拍卖规则-高级用户-->
<div class="modal fade" id="ruleModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               易拍宝拍卖规则
            </h4>
         </div>
         <div class="modal-body">
            <?= $auctionRule->content?>
            
            <p class="center">
            <button class="btn btn-default" data-dismiss="modal">不同意</button>
              <?= Html::a('同意并付款',['auction/submit-guarantee','role'=>'2','goods-guid'=> $model->goods_guid],['class'=>'btn btn-primary','data'=>['method'=>'post']])?>
            </p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default"  id="modal-close"
               data-dismiss="modal">关闭
            </button>
         
         </div>
      </div><!-- /.modal-content -->
</div>
</div><!-- /.modal -->

<!-- 拍卖规则-VIP用户-->
<div class="modal fade" id="vipRuleModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               易拍宝拍卖规则
            </h4>
         </div>
         <div class="modal-body">
            <?= $auctionRule->content?>
            
            <p class="center">
            <button class="btn btn-default"  data-dismiss="modal">不同意</button>
            <?= Html::a('同意并付款',['auction/submit-guarantee','role'=>'3','goods-guid'=> $model->goods_guid],['class'=>'btn btn-success','data'=>['method'=>'post']])?>
            </p>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default"  id="modal-close"
               data-dismiss="modal">关闭
            </button>
         
         </div>
      </div><!-- /.modal-content -->
</div>
</div><!-- /.modal -->

 <section class="imgzoom_pack">
        <div class="imgzoom_x">X</div>
        <div class="imgzoom_img"><img src="" /></div>
    </section>

<script type="text/javascript">
window.onload=function(){
	setTimeout(function(){
        window.addEventListener("popstate", function(e) {  //回调函数中实现需要的功能
            
            location.href='/auction/round-view?id='+"<?= $model->roundid ?>";  //在这里指定其返回的地址
        
        }, false);  
	},100);
}
function pushHistory() {  
    var state = {  
        title: "title",  
        url: "#"  
    };  
    window.history.pushState(state, "title", "#");  
 
}  

pushHistory();




window.onpageshow = function(event){
    if (event.persisted) {
    window.location.reload();
    }
}

function getFragment(){
	$.ajax({
      url:"view-fragment",
      method:'get',
      data:{
          id:'<?= $model->id?>'
      },
      success:function(rs){
		$('#refreshContainer').html(rs);
		console.log('refresh');
		if(intervalCountDowns[0]){
			clearInterval(intervalCountDowns[0]);
		}
		$('#bid-price').focus();
		countDown();
		
      }
	})
}

setInterval(function(){
	getFragment();
},10000);

function countDown(){
	 $(".item-countdown").each(function(){
	   	   var that=$(this);
	        var countTime=$(this).attr('data-time');
	        $(this).downCount({
	    		date: countTime,
	    		offset: +10
	    	}, function () {
	    	//	alert('倒计时结束!');
	    		that.find('.agent-btn').addClass('hide');
	    		that.find('.bid-btn').removeClass('btn-danger');
	        	that.find('.bid-btn').html('已结束');
	        	
	        	
	        	that.find('.prev-btn').removeClass('btn-success');
	        	that.find('.prev-btn').addClass('btn-danger');
	        	that.find('.prev-btn').html('出价');
	    	});
	    });  
}
$(document).ready(function(){
	countDown();
});

var bidType='bid';

$(document).on("click", "#bidSubmit",function(){
	var bidPrice=$('#bid-price').val();
	$('#bid-price-final').html(bidPrice);
	$("#submit-bid").modal("hide");
    $("#confirmBidModal").modal("show");
});
$(document).on("click",".bid-btn",function(){
	var currentPrice=parseInt($('#modelHead').data('currentprice'));
	var deltaPrice=parseInt($('#modelHead').data('deltaprice'));
	$('.current-price').html(currentPrice);
	$('.delta-price').html(deltaPrice);
	$('#bid-price').val(currentPrice+deltaPrice);
    $("#submit-bid").modal("show");
    $('#bid-price').focus();
    bidType='bid';
});

$(document).on("click",".agent-bid-btn",function(){
	var currentPrice=parseInt($('#modelHead').data('currentprice'));
	var deltaPrice=parseInt($('#modelHead').data('deltaprice'));
	$('.current-price').html(currentPrice);
	$('.delta-price').html(deltaPrice);
	$('#agent-bid-price').val(currentPrice+deltaPrice);
	$("#submit-agent").modal("show");
    $('#agent-bid-price').focus();
    bidType='agent-bid';
});

$(document).on("click", "#newAddress",function(){
    $("#AddressModal").modal("show");
});
$(document).on("click", "#final-submit",function(){
	if(bidType=='bid'){
		$('#bid-form').submit();
	}else{
		$('#agent-bid-form').submit();
	}
    
});

$(document).on("click",'#agent-bid-btn',function(){
	var bidPrice=$('#agent-bid-price').val();
	$('#bid-price-final').html(bidPrice);
	$("#submit-agent").modal("hide");
    $("#confirmBidModal").modal("show");
});


// $(".agent-btn").click(function(){
//     $("#submit-agent").modal("show");
// });

$('#submit-guarantee').click(function(){
  $('#ruleModal').modal('show');
});

$('#be-vip').click(function(){
	  $('#vipRuleModal').modal('show');
	});


function checkPrice(price){
	var currentPrice=parseInt($('#modelHead').data('currentprice'));
	var deltaPrice=parseInt($('#modelHead').data('deltaprice'));
	var times=parseInt($('#modelHead').data('auctiontime'));
	if(price==0){
		 modalMsg("价格不能为0");
		    return false;
	}
	if(times==0){
		if(price<currentPrice){
		    modalMsg("价格不能小于当前价格");
		    return false;
		}
	}else{
    	if(price<=currentPrice){
    	    modalMsg("价格不能小于当前价格");
    	    return false;
    	}
	}
	
	if((price-currentPrice)<deltaPrice){
		modalMsg("价格必须大于加价幅度");
	    return false;
	}

	return true;
}

var isBidSubmit=false;
function check(){
	if(isBidSubmit){
		return false;
	}
	var price=parseInt($("#bid-price").val());
    if(!checkPrice(price)){
        return false;
    }
    isBidSubmit=true;
//     showWaiting("正在提交,请稍候...");
     $("#submit-bid").modal("hide");
    return true;
	
}

var isAgentSubmit=false;
function checkAgent(){
	if(isAgentSubmit){
		return false;
	}
	var price=parseInt($("#agent-bid-price").val());
	
    if(!checkPrice(price)){
        return false;
    }
    isAgentSubmit=true;
    $("#submit-agent").modal("hide");
//     showWaiting("正在提交,请稍候...");
    return true;
	
}



function checkAddress(){
	if(!$('#province').val()){
	    modalMsg('请选择省份');
	    return false;
	}
	if(!$('#city').val()){
	    modalMsg('请选择城市');
	    return false;
	}
// 	if(!$('#district').val()){
// 	    modalMsg('请选择区县');
// 	    return false;
// 	}
	if(!$('#address').val()){
	    modalMsg('请填写地址');
	    return false;
	}
	if(!$('#name').val()){
	    modalMsg('请填写姓名');
	    return false;
	}
	if(!$('#mobile').val()){
	    modalMsg('请填写电话');
	    return false;
	}

	showWaiting('正在提交,请稍候...');
	return true;
}

<?php 

$picUrl=empty($picUrl)?yii::$app->params['picUrl']:$picUrl;
$description = empty($description)?yii::$app->params['site-desc']:$description;
?>
wx.ready(function () {  
    //分享到朋友圈  
    wx.onMenuShareTimeline({  
        title: "<?= $this->title?>", // 分享标题  
        link:window.location.href,  
        imgUrl: "<?=$picUrl?>", // 分享图标  
        success: function () {  
   // 分享成功执行此回调函数  
//            alert('success');  
        },  
        cancel: function () {  
//            alert('cancel');  
        }  
    });  

    //分享给朋友  
    wx.onMenuShareAppMessage({  
        title: "<?= $this->title?>", // 分享标题  
        desc: "<?= $description ?>",  
        link:window.location.href,  
        imgUrl:"<?=$picUrl?>", // 分享图标  
        trigger: function (res) {  
            // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回  
        },  
        success: function (res) {  
    // 分享成功执行此回调函数  
        },  
        cancel: function (res) {  
        },  
        fail: function (res) {  
        }  
    });  
});  

document.addEventListener("DOMContentLoaded", function(event){
	$('#goods-desc').find("img").wrap("<div class='list'></div>");
    ImagesZoom.init({
        "elem": ".list"
    });
}, false);


</script>
