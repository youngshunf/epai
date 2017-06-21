<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchNews */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '推荐资讯';
$this->registerJsFile('@web/nivo-slider/jquery.nivo.slider.pack.js');
$this->registerCssFile('@web/nivo-slider/themes/default/default.css');
$this->registerCssFile('@web/nivo-slider/nivo-slider.css');
?>
<style>
.media-object{
	width:100px;
	height:100px;
	margin-left:5px;
}
a{
	color:#333 !important;
}

</style>


    <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_item',            
           'layout'=>"{items}\n{pager}"
      ])?>

