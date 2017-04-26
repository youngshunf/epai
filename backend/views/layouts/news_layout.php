<?php 
use yii\bootstrap\Nav;
use common\models\NewsCate;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="row">
			<div class="col-lg-2  col-md-2">
			<div class="leftmenu">
        <?php  
            $cateid=@$_GET['cateid'];
         /*   $menuItems[] = ['label' => '资讯分类', 'url' => ['/news/index']  ];    */
           $newsCate=NewsCate::find()->all();
           foreach ($newsCate as $cate){
               $menuItems[] = ['label' =>$cate->name, 'url' => ['/news/news?cateid='.$cate->cateid],'active'=>$cate->cateid==$cateid];
           }                   
            echo Nav::widget([
                'options' => ['class' => 'nav nav-pills nav-stacked '],
                'items' => $menuItems,
            ]);      
		?>
		</div>
        </div>
        <div class="col-lg-10 col-md-10"> 
         <div class="panel-white">     
        <?= $content ?>
        </div>
        </div>
         </div>
<?php $this->endContent();?>
