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
$this->registerJsFile('@web/js/PCASClass.js',['position'=> View::POS_HEAD]);
?>

<div>
<a href="#">
<img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo?>" class="img-responsive">
</a>
    <div class="row">
  
  <div class="col-md-6">
  <div class="panel-white">
    <h5><?=$model->name ?></h5>
 
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
                 <td>当前加价幅度:<span class="red-sm">￥<?= $delta_price?></span></td>               
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
                 </table>
                 
                 </div>
                  <div class="  center">
                  <?php if(yii::$app->user->identity->status==0){?>
					<span class="red">您已被禁止参与拍卖,请及时购买已经成交的拍品.</span>
					<?php }else{?>
					<a  class="btn btn-lg btn-danger  bid-btn" >出价</a>
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
                 <td>加价幅度:<span class="red-sm">￥<?= $model->delta_price?></span></td>                
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
                 
                   <?php if(!empty($model->fixed_price)){?>
                     <tr>
                 <td colspan='2'><span>一口价:<i class="red"> ￥<?= $model->fixed_price?></i></span></td>
                 </tr>
                 
                 <?php }?>
                 
                 </table>
                 
                 </div>
                
                  <div class="center">
                 
					 &nbsp;
						 <?php if(!empty($model->fixed_price)&&$model->status!=3){?>
						 
						  <?php if(!yii::$app->user->isGuest){?>
					   <ul class="list-group">
                            <?php $address=Address::findOne(['user_guid'=>yii::$app->user->identity->user_guid,'is_default'=>1]);
                                if(!empty($address)){?>
                           <li class="list-group-item">收货地址:
                           <?= $address->province?>   <?= $address->city?>   <?= $address->district?>   <?= $address->address?>   <?= $address->company?>   <?= $address->name?>   <?= $address->phone?>
                            
                           </li>
                           <?php }?>
                           <li class="list-group-item" id="newAddress"><span class="glyphicon glyphicon-plus" style="color: rgb(255, 140, 60);"></span>新增收货地址</li>
                           </ul>
                           <?php }?>
                           
					 <?= Html::a('一口价购买',['auction/fixed-buy','goods_guid'=>$model->goods_guid],['class'=>'btn btn-danger','data-confirm'=>'您确定要一口价购买此拍品?'])?>
					<?php }elseif($model->status==3){?>
					<span class="btn btn-default">已售出</span>
					<?php }?>
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
                <p class="red">已结束</p>
                   <div class="auction-info">
                 <table>
                 <tr>
                 <td>起拍价格:<span class="red-sm">￥<?= $model->start_price?></span></td>
                 <td>加价幅度:<span class="red-sm">￥<?= $model->delta_price?></span></td>
                 
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
                 
                 </table>
                 <p class="center">
                 
                 </p>
                 </div>
   
   
    <?php }?>
    
    <?php if(!yii::$app->user->isGuest){
    if(yii::$app->user->identity->user_guid==$model->deal_user&&$model->status==2){
        ?>
     <div class="center">
     <a class="btn btn-danger" href="<?= Url::to(['buy-auction','id'=>$model->id])?>">立即购买</a>
    </div>
    <?php } }?>
    
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
     <div class="col-lg-12">
     <div class="panel-white">
   <h5>商品介绍</h5>
  <?= $model->desc?>
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
            	<form action="<?= Url::to(['submit-bid'])?>" method="post" onsubmit="return check()">
            	<p>当前价格:<span class="red">￥<?= $model->current_price?></span></p>
            	<p>当前加价幅度:<span class="red">￥<?= $delta_price?></span></p>
            	<div class="form-group required" >
            	<label class="label-control">竞拍价格:</label>
            	<input type="text" name="bid-price" id="bid-price" class="form-control">
            	<span class="red-sm hide">*竞拍价格不能低于当前价格,且为加价幅度的整数倍</span>
            	</div>
            	<input type="hidden" name="goods-guid"  value="<?= $model->goods_guid?>">
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
            	<form action="<?= Url::to(['submit-agent'])?>" method="post" onsubmit="return checkAgent()">
            	<p>当前价格:<span class="red">￥<?= $model->current_price?></span></p>
            	<p>当前加价幅度:<span class="red">￥<?= $delta_price?></span></p>
            	
            	<div class="form-group required" >
            	<label class="label-control">最高价格:</label>
            	<input type="text" name="agent-price" id="agent-price" class="form-control">            
            	</div>
            	<input type="hidden" name="goods-guid"  value="<?= $model->goods_guid?>">
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

<script type="text/javascript">

setInterval(function(){
	location.reload();
},12000);

$(document).ready(function(){
    var that=$(this);
    $(".item-countdown").each(function(){
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

$(".bid-btn").click(function(){
    $("#submit-bid").modal("show");
});

$("#newAddress").click(function(){
    $("#AddressModal").modal("show");
});

$(".agent-btn").click(function(){
    $("#submit-agent").modal("show");
});

$('#submit-guarantee').click(function(){
  $('#ruleModal').modal('show');
});

$('#be-vip').click(function(){
	  $('#vipRuleModal').modal('show');
	});

var currentPrice=parseInt(<?= $model->current_price?>);
var deltaPrice=parseInt(<?= $delta_price?>);
var times=parseInt(<?= $auctionTimes?>);
function checkPrice(price){
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
	
	if((price-currentPrice)%deltaPrice!=0){
		modalMsg("价格必须为加价幅度的整数倍");
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
	var price=parseInt($("#agent-price").val());
	
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
	if(!$('#district').val()){
	    modalMsg('请选择区县');
	    return false;
	}
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

</script>
