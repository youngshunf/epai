<?php 


use yii\widgets\Breadcrumbs;
use common\models\News;
use yii\helpers\Url;
use common\models\CommonUtil;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="container ">
  
    <div class="row">
              <div class="col-lg-9 col-md-9"> 
                     
                <?= $content ?>
             
                </div>
                
			<div class="col-lg-3  col-md-3">
			<div class="panel-white">
        <?php 
        $recomendNews=News::find()->andWhere(['cateid'=>'1'])->limit(8)->orderBy('created_at')->all();
        ?>
        
        <h5>推荐资讯 </h5>
        <?php 
        foreach ($recomendNews as $model){
        ?>
        
        <a href="<?= Url::to(['view','id'=>$model['newsid']])?>">
    	<div class=" wish-list">     
        <div class="media-container">  
         <p ><?= $model->title?></p>    
        </div>    
        </div>       
		</a>
        <?php }?>
        <p class="center"><a href="<?= Url::to('/news/rec-more')?>">更多</a></p>
    		</div>
    		
            </div>
      
         </div>
      </div>
<?php $this->endContent();?>
