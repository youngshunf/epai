<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '知文玩', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
	<div class="panel-white">
<div class="center">
  <h4><?= Html::encode($this->title) ?></h4>
</div>
  
<div class="news-info center">
   <span class="glyphicon glyphicon-time" ></span>  发布时间:<?= CommonUtil::fomatDate($model->created_at)?>
    &nbsp;&nbsp; &nbsp; &nbsp;  <span class="glyphicon glyphicon-eye-open" ></span> 浏览次数:  <?= $model->count_view?>
</div>
 

   
    <?= $model->content?>


</div>

 <div class="panel-white"> 
    <h5>相关资讯</h5>
     		      <?php 
        foreach ($relativeNews as $model){
        ?>
        
        <a href="<?= Url::to(['view','id'=>$model['newsid']])?>">
    <div class=" wish-list">     
        <div class="media-container">  
         <p ><?= $model->title?></p>    
        </div>    
        </div>       
      
  
</a>
        <?php }?>
 </div>
