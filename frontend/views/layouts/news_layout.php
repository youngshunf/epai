<?php 


use yii\widgets\Breadcrumbs;
use common\models\News;
use yii\helpers\Url;
use common\models\CommonUtil;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="container ">
       <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
    <div class="row">
              <div class="col-lg-9 col-md-9"> 
                
              
                     
                <?= $content ?>
             
                </div>
                
			<div class="col-lg-3  col-md-3">
			<div class="panel-white">
        <?php 
        $recomendNews=News::find()->andWhere(['is_recommend'=>1])->limit(10)->orderBy('created_at')->all();
        $hotNews=News::find()->limit(10)->orderBy('count_view desc')->all();
        ?>
        
        <h5>推荐资讯</h5>
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
        
        
    		</div>
    		
    		<div class="panel-white">
    		<h5>一周热门</h5>
    		      <?php 
        foreach ($hotNews as $model){
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
    		
            </div>
      
         </div>
      </div>
<?php $this->endContent();?>
