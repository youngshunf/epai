<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchLotteryGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '我的收藏';

?>
<style>
.mui-table-view-cell p {
  margin-bottom: 10px;
}
</style>
    <?= ListView::widget([
            'dataProvider'=>$goods,
            'itemView'=>'_goods_item',            
           'layout'=>"{items}\n{pager}"
      ])?>



