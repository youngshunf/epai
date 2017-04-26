<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchLotteryGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '我的夺宝';
$this->params['breadcrumbs'][] = ['label' => '个人中心', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_lottery_item',            
           'layout'=>"{items}\n{pager}"
      ])?>



