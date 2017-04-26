<?php 


use yii\widgets\Breadcrumbs;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="container ">
       <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
    <div class="row">
              <div class="col-lg-12 col-md-12"> 
                 <div class="panel-white"> 
              
                     
                <?= $content ?>
                </div>
                </div>
                

      
         </div>
      </div>
<?php $this->endContent();?>
