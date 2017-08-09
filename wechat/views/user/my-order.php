<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchLotteryGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '我的订单';
?>

<div class="panel-white">

     <div class="control">
					<a class="item <?php if($is_pay==3) echo "active";?>" href="<?= Url::to(['my-order'])?>">
				全部
			</a>
				<a class="item  <?php if($is_pay=='1') echo "active";?>" href="<?= Url::to(['my-order','is_pay'=>'1'])?>">
				已支付
			</a>
					<a class="item <?php if($is_pay=='0') echo "active";?>" href="<?= Url::to(['my-order','is_pay'=>'0'])?>">
				未支付
			</a>		
				</div>
</div> 

    <?= ListView::widget([
            'dataProvider'=>$orderData,
            'itemView'=>'_order_item',            
           'layout'=>"{items}\n{pager}"
      ])?>



