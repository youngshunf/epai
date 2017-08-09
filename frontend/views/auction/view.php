<?php

use yii\helpers\Html;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Address;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '天天易拍', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/PCASClass.js',['position'=> View::POS_HEAD]);

?>

    <h5><?= Html::encode($this->title) ?></h5>

    <div class="row">
    <div class="col-md-6 ">
    <div class="panel-white">
   <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo?>" class="img-responsive">
<br>
<br>
   <p class="center">
 <a href="<?= Url::to(['site/auction-rules'])?>">易拍宝拍卖规则</a>
 </p>
  </div>
  </div>
  <div class="col-md-6">
  <div class="panel-white">
    <h4><?=$model->name ?></h4>
 
    <?php 
    $now=time();
    if($now>=$model->start_time&&$now<=$model->end_time){?>
       <p>当前价格:<span class="red">￥<?= $model->current_price?></span></p>
    	 <div class="item-countdown" data-time="<?= date("m/d/Y H:i:s",$model->end_time)?>" >
				 &nbsp;<span class="countdown-text">距结束</span>&nbsp;&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
                 <p>
                
                 </p>
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
                 <td><span>起拍时间:<i class="green"><?= CommonUtil::fomatHours($model->start_time)?></i></span></td>
                 <td> <span>结束时间:<i class="green"><?= CommonUtil::fomatHours($model->end_time)?></i></span></td>
                 </tr>
                 </table>
                 
                 </div>
                  <div class="  center">
					<a  class="btn btn-warning agent-btn">代理出价</a>  &nbsp;
					<a  class="btn btn-danger  bid-btn" >出价</a>
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
                 <td><span>起拍时间:<i class="green"><?= CommonUtil::fomatHours($model->start_time)?></i></span></td>
                 <td> <span>结束时间:<i class="green"><?= CommonUtil::fomatHours($model->end_time)?></i></span></td>
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
    <?php }else if($now>$model->end_time){?>
                  <p>成交价格:<span class="red">￥<?= $model->current_price?></span></p>
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
                 <td><span>起拍时间:<i class="green"><?= CommonUtil::fomatHours($model->start_time)?></i></span></td>
                 <td> <span>结束时间:<i class="green"><?= CommonUtil::fomatHours($model->end_time)?></i></span></td>
                 </tr>
                 
                 </table>
                 
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
            'value'=>function ($model){
                $price= "<span class='red-sm'>￥".$model->price."</span>";
             /*    if($model->is_agent){
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
   <h5>商品描述</h5>
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
         <?php }elseif($guarantee==0){ ?>
         <p>您还未交保证金,请先交保证金再参与竞拍</p>
         <p><label>保证金额:</label><span class="red">￥200</span></p>
           <div class="form-group center ">
          <?= Html::a('提交保证金',['site/submit-guarantee','role'=>'2','goods-guid'=> $model->goods_guid],['class'=>'btn btn-primary','data'=>['method'=>'post']])?>
            <?= Html::a('成为VIP用户',['site/submit-guarantee','role'=>'3','goods-guid'=> $model->goods_guid],['class'=>'btn btn-success','data'=>['method'=>'post']])?>
                          
             </div> 
             <p>每次拍卖均须提交保证金,成为VIP用户后参加拍卖不需要缴纳保证金。VIP用户保证金不退还。</p>
              <a href="<?= Url::to(['site/auction-rules'])?>" class="pull-right">易拍宝拍卖规则</a>
             <p class="clear"></p> 
                         
               <?php }elseif($guarantee==2){?>
             <p class="red bold">您已提交保证金退款申请,保证金正在退款中,暂时不能参加拍卖。保证金退款成功后可重新缴纳保证金参与拍卖!</p>
             
             
             <?php }else{?>
            	<form action="<?= Url::to(['submit-bid'])?>" method="post" onsubmit="return check()">
            	<p>当前价格:<span class="red">￥<?= $model->current_price?></span></p>
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
         <?php }elseif($guarantee==0){ ?>
      
         <p>您还未交保证金,请先交保证金再参与竞拍</p>
         <p><label>保证金额:</label><span class="red">￥200</span></p>
           <div class="form-group center ">
          <?= Html::a('提交保证金',['site/submit-guarantee','role'=>'2','goods-guid'=> $model->goods_guid],['class'=>'btn btn-primary','data'=>['method'=>'post']])?>
            <?= Html::a('成为VIP用户',['site/submit-guarantee','role'=>'3','goods-guid'=> $model->goods_guid],['class'=>'btn btn-success','data'=>['method'=>'post']])?>
                          
             </div> 
             <p>每次拍卖均须提交保证金,成为VIP用户后参加拍卖不需要缴纳保证金。VIP用户保证金不退还。</p>
             <a href="<?= Url::to(['site/auction-rules'])?>" class="pull-right">易拍宝拍卖规则</a>
             <p class="clear"></p> 
             
                <?php }elseif($guarantee==2){?>
             <p class="red bold">您已提交保证金退款申请,保证金正在退款中,暂时不能参加拍卖。保证金退款成功后可重新缴纳保证金参与拍卖!</p>
             <?php }else{?>
            	<form action="<?= Url::to(['submit-agent'])?>" method="post" onsubmit="return checkAgent()">
            	<p>当前价格:<span class="red">￥<?= $model->current_price?></span></p>
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

<script type="text/javascript">



$(document).ready(function(){
	
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


$("#newAddress").click(function(){
    $("#AddressModal").modal("show");
});
$(".bid-btn").click(function(){
    $("#submit-bid").modal("show");
});

$(".agent-btn").click(function(){
    $("#submit-agent").modal("show");
});

var currentPrice=parseInt(<?= $model->current_price?>);
var deltaPrice=parseInt(<?= $model->delta_price?>);
var times=parseInt(<?= $auctionTimes?>);
function checkPrice(price){
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

function check(){
	var price=parseInt($("#bid-price").val());
    if(!checkPrice(price)){
        return false;
    }

    showWaiting("正在提交,请稍候...");
    return true;
	
}

function checkAgent(){
	var price=parseInt($("#agent-price").val());
	
    if(!checkPrice(price)){
        return false;
    }

    showWaiting("正在提交,请稍候...");
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

</script>
