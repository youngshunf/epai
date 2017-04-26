<?php 
use yii\bootstrap\Nav;
use common\models\NewsCate;
use common\models\AuctionCate;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="row">
			<div class="col-lg-2  col-md-2">
			<div class="leftmenu">
        <?php  
            $cateid=@$_GET['cateid'];
           $menuItems[] = ['label' => '拍品分类', 'url' => ['/auction/index']  ];   
           $menuItems[] = ['label' => '拍卖专场', 'url' => ['/auction/round']  ];
           $menuItems[] = ['label' => '天天易拍', 'url' => ['/auction/ongoing']  ];
           $menuItems[] = ['label' => '全部拍品', 'url' => ['/auction/goods'],'active'=>yii::$app->controller->action->id=='goods'&&empty($cateid)  ];
           $AuctionCate=AuctionCate::find()->all();
           foreach ($AuctionCate as $cate){
               $menuItems[] = ['label' =>$cate->name, 'url' => ['/auction/goods?cateid='.$cate->cateid],'active'=>$cate->cateid==$cateid];
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
