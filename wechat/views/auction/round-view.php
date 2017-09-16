<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchAuctionGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $round->name;
$this->params['breadcrumbs'][] = ['label'=>'专场拍卖','url'=>'round'];
$this->params['breadcrumbs'][] = $this->title;
$description = empty($description)?yii::$app->params['site-desc']:$description;
?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<div class="row">
    <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemOptions' => ['class' => 'item'],
            'itemView'=>'_item',            
            'layout'=>"{items}\n{pager}",
          'pager' => ['class' => \kop\y2sp\ScrollPager::className()]
      ])?>

</div>

<script>
wx.ready(function () {  
    //分享到朋友圈  
    wx.onMenuShareTimeline({  
        title: "<?= $this->title?>", // 分享标题  
        link:window.location.href,  
        imgUrl: "{pigcms:$res['pic']}", // 分享图标  
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