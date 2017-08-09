<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchLotteryGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '我的优惠券';

?>
<style>
.mui-table-view-cell p {
  margin-bottom: 10px;
}
</style>
    <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_coupon_item',            
           'layout'=>"{items}\n{pager}"
      ])?>



