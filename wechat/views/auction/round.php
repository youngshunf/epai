<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchAuctionGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '小火文玩拍卖';
$this->params['breadcrumbs'][] = $this->title;
$description = empty($description)?yii::$app->params['site-desc']:$description;
?>
<style>
.row {
	margin:0 !important;
	  padding-bottom: 5px;
}
 .col-sm-6, .col-md-6, .col-lg-6{
  padding-right: 0px !important;
  padding-left: 0px !important;
}

 .col-xs-6{
  padding-right: 5px !important;
  padding-left: 5px !important;
 	  padding-bottom: 5px;
}
</style>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_round_item',            
           'layout'=>"{items}\n{pager}"
      ])?>
  


<script type="text/javascript">
window.addEventListener("popstate", function(e) {  //回调函数中实现需要的功能
    
	WeixinJSBridge.call('closeWindow');

}, false);  

function pushHistory() {  
    var state = {  
        title: "title",  
        url: "#"  
    };  
    window.history.pushState(state, "title", "#");  
 
}  

pushHistory();
sessionStorage.removeItem('$pageIndex');
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

    wx.ready(function () {  
        //分享到朋友圈  
        wx.onMenuShareTimeline({  
            title: <?= $this->title?>, // 分享标题  
            link:window.location.href,  
            imgUrl: "{pigcms:$res['pic']}", // 分享图标  
            success: function () {  
       // 分享成功执行此回调函数  
//                alert('success');  
            },  
            cancel: function () {  
//                alert('cancel');  
            }  
        });  

        //分享给朋友  
        wx.onMenuShareAppMessage({  
            title: <?= $this->title?>, // 分享标题  
            desc: <?= $description ?>,  
            link:window.location.href,  
            imgUrl: "{pigcms:$res['pic']}", // 分享图标  
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