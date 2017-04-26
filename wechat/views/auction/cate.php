<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchAuctionGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分类浏览';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_cate_item',            
           'layout'=>"{items}\n{pager}"
      ])?>

</div>
