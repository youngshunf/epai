<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchNews */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '新闻资讯';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/nivo-slider/jquery.nivo.slider.pack.js');
$this->registerCssFile('@web/nivo-slider/themes/default/default.css');
$this->registerCssFile('@web/nivo-slider/nivo-slider.css');
?>
<style>
.media-object{
	width:150px;
}

</style>

  <div class="slider-wrapper theme-default">
       <div id="slider" class="nivoSlider">
       <?php foreach ($model as $k=>$v){?>
          <a href="<?= Url::to(['view','id'=>$v->newsid])?>"><img src="<?= yii::getAlias('@photo').'/'.$v->path.'mobile/'.$v->photo?> " data-thumb="<?= yii::getAlias('@photo').'/'.$v->path.'thumb/'.$v->photo?>" alt="<?= $v->title?>"  title="<?= $v->title?>" /></a>         
         <?php }?>
     </div>
   </div>

    <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_item',            
           'layout'=>"{items}\n{pager}"
      ])?>

<script type="text/javascript">
$(window).load(function() {
    $('#slider').nivoSlider();
});
</script>
