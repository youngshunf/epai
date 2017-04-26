<?php 
use yii\bootstrap\Nav;
use common\models\AuctionCate;
use yii\widgets\Breadcrumbs;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="container ">
 
     <div class="row">
     <div class="col-lg-12  col-md-12">
          <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
   </div>
			<div class="col-lg-12  col-md-12">
			<div class="panel-white">
			<table class="table table-striped">
			<tr>
			<td width="80px" class="center "  style="  line-height: 35px;font-weight:bold;">分类</td>
			<td>
			   <?php  
            $cateid=@$_GET['cateid'];
           $menuItems[] = ['label' => '全部拍品', 'url' => ['/auction/index'],'active'=>empty($cateid)  ];   
           $AuctionCate=AuctionCate::find()->all();
           foreach ($AuctionCate as $cate){
               $menuItems[] = ['label' =>$cate->name, 'url' => ['/auction/index?cateid='.$cate->cateid],'active'=>$cate->cateid==$cateid];
           }                   
            echo Nav::widget([
                'options' => ['class' => 'nav nav-pills  '],
                'items' => $menuItems,
            ]);     
		?>
			
			</td>
			</tr>
			
			</table>
     
		</div>
        </div>  
        <div class="col-lg-12 col-md-12"> 
             <div class="panel-white">     
            <?= $content ?>
            </div>
        </div>
         </div>
         </div>
<?php $this->endContent();?>
