<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchAuctionGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $round->name;
$this->params['breadcrumbs'][] = ['label'=>'专场拍卖','url'=>'round'];
$this->params['breadcrumbs'][] = $this->title;
$description = empty($description)?yii::$app->params['site-desc']:$description;
$this->registerJsFile('@web/js/moment.min.js',['position'=> View::POS_HEAD]);
 $this->registerJsFile('@web/js/vue.min.js',['position'=> View::POS_HEAD]);
$this->registerJsFile('@web/js/vue-scroller.min.js',['position'=> View::POS_HEAD]);
?>
<style>
[v-cloak]{
	display:none
}
</style>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<div  id="question" v-cloak>
    <?php
//     echo ListView::widget([
//             'dataProvider'=>$dataProvider,
//             'itemOptions' => ['class' => 'item'],
//             'itemView'=>'_item',            
//             'layout'=>"{items}\n{pager}",
//           'pager' => ['class' => \kop\y2sp\ScrollPager::className()]
//       ]); 
      
//       echo ListView::widget([
//           'id' => 'my-listview-id',
//           'itemOptions' => ['class' => 'item'],
//           'itemView'=>'_item',
//           'layout'=>"{items}\n{pager}",
//           'pager' => [
//               'class' => InfiniteScrollPager::className(),
//               'widgetId' => 'my-listview-id',
//               'itemsCssClass' => 'items',
//           ],
//       ]);
    
    ?>



<scroller :on-refresh="refresh"
            :on-infinite="infinite"
            ref="scroller"
            no-data-text="没有更多拍品"
            >
            
    <div class="row auction-item" v-for="(item,index) in list"   >
    <a :href="'view?id='+item.id">
    	<div class="col-xs-4">
    	<div style="position:relative">
    		
					<img :src="baseUrl+item.path+'thumb/'+item.photo"  class="img-responsive">
		   
		    <div class="item-bid-box">
                    <span class="side-num">{{item.count_auction}}</span>次出价
				</div>
			</div>
		  </div>
		  <div class="col-xs-8">
            <ul class="auction">
            <li class="pai-item">
     		
		    <div v-if="time>=item.start_time&& time<=item.end_time">
		    <div class="pai-content"  :class="[ ((item.end_time-time)>=0 && (item.end_time-time)<=60)?'auction-alert':'']">
				 <h5 class="ellipsis clamp-2">{{item.name}}</h5>
				 <p class="no-margin">起拍价格:<i class="red-sm">￥ {{item.start_price}}</i></p>
				  <p class="no-margin"><span class=""> 当前价格:<i class="red">￥ {{item.current_price}}</i></span></p>				 
				 <div class="item-countdown"  :time="getformatTime(item.end_time)" >
				
				 &nbsp;<span class="countdown-text">距结束</span>&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
                
                  <div class="item-button">
					<a :href="'view?id='+item.id" class="btn btn-default btn-sm">围观({{item.count_view}})</a>
					<a :href="'view?id='+item.id" class="btn btn-danger bid-btn btn-sm" >出价</a>
				 </div>
				 <div class="clear"></div>
                 </div>
								 				 
				</div>
				
			</div>
			<div v-if="time < item.start_time ">
		    <div class="pai-content"  :class="[ ((item.end_time-time)>=0 && (item.end_time-time)<=60)?'auction-alert':'']">
				 <h5 class="ellipsis clamp-2">{{item.name}}</h5>
				 <p class="no-margin">起拍价格:<i class="red-sm">￥ {{item.start_price}}</i></p> 
				 <p class="no-margin"> 当前价格:<i class="red">￥ {{item.current_price}}</i></p>				 
				 <div class="item-countdown"  :time="getformatTime(item.start_time)" >
				 &nbsp;<span class="countdown-text">距开始</span>&nbsp;
				 <p class=" pai-countdown" >
                        <span class="J_TimeLeft prev"><i class="days">00</i>天<i class="hours">00</i> 时 <i class="minutes">00</i> 分 <i class="seconds">00</i> 秒</span>
                 </p>
                  <div class="item-button">
					<a :href="'view?id='+item.id" class="btn btn-default btn-sm">围观({{item.count_view}})</a>  &nbsp;
					<a :href="'view?id='+item.id" class="btn btn-danger bid-btn btn-sm" >出价</a>
				 </div>
				 <div class="clear"></div>
                 </div>
								 				 
				</div>
				
			</div>
			
			<div v-if="time>item.end_time">
		    <div class="pai-content"  :class="[ ((item.end_time-time)>=0 && (item.end_time-time)<=60)?'auction-alert':'']">
				 <h5 class="ellipsis clamp-2">{{item.name}}</h5>
				 <p class="no-margin">起拍价格:<i class="red-sm">￥ {{item.start_price}}</i></p>
				 <p class="no-margin"> <span class=""> 当前价格:<i class="red">￥ {{item.current_price}}</i></p>				 
				 <div  >
				 <span class="organe">
				 {{getStatus(item)}}
				 </span>&nbsp;&nbsp;
                  <div class="item-button">
					<a :href="'view?id='+item.id" class="btn btn-default btn-sm">围观({{item.count_view}})</a> 
					<a :href="'view?id='+item.id" class="btn btn-danger bid-btn btn-sm" >出价</a>
				 </div>
				 <div class="clear"></div>
                 </div>
								 				 
				</div>
				
			</div>
			
           </li>
            </ul>
           </div>
            </a>
      </div>
<!--     <div v-if="infiniteCount >= 2" slot="infinite" class="center">没有更多数据</div> -->
  </scroller>


</div>
<script>
moment.locale('zh-CN');
var roundid="<?= $round->id?>";
var baseUrl="<?= yii::$app->params['photoUrl']?>";
var time="<?= time()?>";

var app= new Vue({
    el:'#question',
    data: function(){
       return{
          query:{},
          list:[],
          baseUrl:baseUrl,
          time:moment().unix(new Date()),
          first:true,
          infiniteCount:0,
          reload:false,
        }
    },
    
    created: function () {
      this.query.roundid=roundid;
      this.query.pageIndex= sessionStorage.getItem('$pageIndex') || 1;
      console.log(this.query.pageIndex);
      this.query.pageSize=5;
//       this.getList();
    },

    methods: {
      getLeftTime:function(item){
		var leftTime=item.end_time-this.time;
		return leftTime;
      },
      getStatus:function(item){
		var statusArr={
				 '0':'预展',
			    '1':'拍卖中',
			    '2':'已成交',
			    '3':'已成交',
			    '4':'流拍',
			    '5':'未达到保留价流拍',
			    '99':'已结束',
		}
		return statusArr[item.status];
      },
      getformatTime:function(otime){
          
    		var time= moment(otime*1000).format('MM/DD/YYYY HH:mm:ss');
    		return time;
          },
      getList:function(direct){
        var self=this;
        this.time=moment().unix(new Date());
        var lastIndex=sessionStorage.getItem('$pageIndex') || 1;
        if(!this.first && lastIndex ==this.query.pageIndex){
        	if(direct=='up' ){
				  if (self.$refs.scroller)
					  self.$refs.scroller.finishPullToRefresh();
			}else{
				if (self.$refs.scroller){
					self.$refs.scroller.finishInfinite(self.infiniteCount==2)
				}
					
			}
			return;
        }
       
        sessionStorage.setItem('$pageIndex',this.query.pageIndex);
		$.ajax({
			method:'get',
			url:'get-roundlist',
			data:{
				data:self.query
			},
			success:function(rs){
		
				if(rs && typeof rs=='string'){
                   rs=JSON.parse(rs);
				}
			    if(rs.length==0 ){
			    	self.query.pageIndex--;
			    	if(self.query.pageIndex<1){
			    		self.query.pageIndex=1;
			    	}
			    	sessionStorage.setItem('$pageIndex',self.query.pageIndex);
			    }
				if((rs.length==0 || (rs.length<self.query.pageSize))){
					self.infiniteCount=2;
				}
				 if(self.first){
						self.first=false;
			    }
				if(self.query.pageIndex==1 && rs.length !=0){
					self.list=rs;
				}else{
					if(direct=='up'){
						for(var i in rs){
							self.list.unshift(rs[rs.length-i-1]);
						}
					}else{
						self.list=self.list.concat(rs);
					}
				}
				
				if(intervalCountDowns.length>0){
					for(var i in intervalCountDowns){
						if(intervalCountDowns[i]){
							clearInterval(intervalCountDowns[i]);
						}
					}
				}
				self.$nextTick(function(){
					setTimeout(function(){
						self.initCountDown();
					},100);
					
				})
				
				if(direct=='up' ){
					  if (self.$refs.scroller)
						  self.$refs.scroller.finishPullToRefresh();
				}else{
					if (self.$refs.scroller){
						self.$refs.scroller.finishInfinite(self.infiniteCount==2)
					}
						
				}
				
			},
			error:function(e){
				console.log(e);
			}
		})
      },
      refresh: function () {
        var self = this;
        if(this.first){
        	this.query.pageIndex=sessionStorage.getItem('$pageIndex') || 1;
        	sessionStorage.removeItem('$pageIndex');
        }else{
       	  this.query.pageIndex--;
             if(this.query.pageIndex<=1){
             	this.query.pageIndex=1;
             }
        }
       if(self.infiniteCount==2){
    	   this.query.pageIndex=1;
       }
        
        this.getList('up');
      },

      infinite: function () {
        var self = this;
        if(this.first){
        	this.query.pageIndex=sessionStorage.getItem('$pageIndex') || 1;
        	sessionStorage.removeItem('$pageIndex');
        }else{
        	this.query.pageIndex++;
        }
        
        this.getList('down');
      },
      initCountDown:function(){
          var i=0;
    	  $(".item-countdown").each(function(){
  	        var that=$(this);
  	        var countTime=$(this).attr('time');
  	        $(this).downCount({
  	    		date: countTime,
  	    		offset: +10,
  	    		timer:i
  	    	}, function () {
  	    	//	alert('倒计时结束!');
  	        	that.find('.bid-btn').removeClass('btn-danger');
  	        	that.find('.bid-btn').html('<span class="organe">已结束</span>');
  	    	});
  	    	i++;
  	     }); 
      }  
    }
  });


window.onload=function(){

function pushHistory() {  
    var state = {  
        title: "title",  
        url: "#"  
    };  
    window.history.pushState(state, "title", "#");  
 
}  
pushHistory();
setTimeout(function(){
	window.addEventListener("popstate", function(e) {  //回调函数中实现需要的功能
	    location.href='/auction/round';  //在这里指定其返回的地址
	}, false);  
},100);
}

window.onpageshow = function(event){
    if (event.persisted) {
   	 window.location.reload();
    }
}

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
        imgUrl: "<?=$picUrl?>", // 分享图标  
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