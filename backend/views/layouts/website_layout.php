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
           $menuItems[] = ['label' => '首页轮播大图', 'url' => ['/website/index'],'active'=>yii::$app->controller->action->id=='index'  ];   
           $menuItems[] = ['label' => '拍品征集', 'url' => ['/website/collect?id=1'] ,'active'=>yii::$app->controller->action->id=='collect'  ];
           $menuItems[] = ['label' => '网上典当', 'url' => ['/website/mortage?id=2'],'active'=>yii::$app->controller->action->id=='mortage'   ];
           $menuItems[] = ['label' => '联系我们', 'url' => ['/website/contact-us?id=3'],'active'=>yii::$app->controller->action->id=='contact-us'   ];
           $menuItems[] = ['label' => '拍卖规则', 'url' => ['/website/auction-rules?id=4'],'active'=>yii::$app->controller->action->id=='auction-rules'   ];
           
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
