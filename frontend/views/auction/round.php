<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchAuctionGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '专场拍卖';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.row {
	margin:0 !important;
	  padding-bottom: 15px;
}
 .col-sm-6, .col-md-6, .col-lg-6{
  padding-right: 0px !important;
  padding-left: 0px !important;
}
.auction {
	min-height:303px;
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
    

</script>