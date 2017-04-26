<?php 
use yii\bootstrap\Nav;
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="row">
			<div class="col-lg-2  col-md-2">
			<div class="leftmenu">
        <?php  
           $menuItems[] = ['label' => '会员管理', 'url' => ['/user/index']  ];   
          /*  $menuItems[] = ['label' => '虚拟用户', 'url' => ['/user/virtual']  ]; */
           $menuItems[] = ['label' => '管理员管理', 'url' => ['/user/admin']];   
           $menuItems[] = ['label' => 'VIP用户保证金退款', 'url' => ['/user/vip-refund']];
           $menuItems[] = ['label' => '卖家身份审核', 'url' => ['/user/merchant-auth']];
           $menuItems[] = ['label' => '卖家保证金退款', 'url' => ['/user/merchant-refund']];
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
