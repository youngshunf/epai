<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchAuctionGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '拍品预展';
$this->params['breadcrumbs'][] = $this->title;
?>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<div class="row">
    <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_prev_item',            
           'layout'=>"{items}\n{pager}"
      ])?>

</div>
