<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\FlatlabAsset;
use frontend\widgets\Alert;
/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
FlatlabAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'E拍网管理后台',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
          
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => '登录', 'url' => ['/site/login']];               
            } else {
				$menuItems[] = [
					'label' => '首页', 
					'url' => ['/site/index'],
				    'active'=>yii::$app->controller->id=='site'
				];
				$menuItems[] = [
				    'label' => '网站管理',
				    'url' => ['/website/index'],
				    'active'=>yii::$app->controller->id=='website'
				];
				$menuItems[] = [
					'label' => '用户管理',
					 'url' => ['/user/index'],
				    'active'=>yii::$app->controller->id=='user'
				];
				$menuItems[] = [
				    'label' => '拍卖管理',
				    'url' => ['/auction/index'],
				    'active'=>yii::$app->controller->id=='auction'
				];
// 				$menuItems[] = [
// 				    'label' => '第三方拍品',
// 				    'url' => ['/sp-auction/index'],
// 				    'active'=>yii::$app->controller->id=='sp-auction'
// 				];
// 				$menuItems[] = [
// 				    'label' => '一元夺宝',
// 				    'url' => ['/lottery/index'],
// 				    'active'=>yii::$app->controller->id=='lottery'
// 				];		
// 				$menuItems[] = [
// 				    'label' => 'E拍商城',
// 				    'url' => ['/mall/index'],
// 				    'active'=>yii::$app->controller->id=='mall'
// 				];
				$menuItems[] = [
				    'label' => '资讯管理',
				    'url' => ['/news/index'],
				    'active'=>yii::$app->controller->id=='news'
				];
				$menuItems[] = [
				    'label' => '订单管理',
				    'url' => ['/order/index'],
				    'active'=>yii::$app->controller->id=='order'
				];
				$menuItems[] = [
				    'label' => '优惠券管理',
				    'url' => ['/coupon/index'],
				    'active'=>yii::$app->controller->id=='coupon'
				];
                $menuItems[] = [
                    'label' => '注销 (' . yii::$app->user->identity->username. ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
				
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
        </div>
    </div>

    <div id="overlay">
            <div class="overlay-body">
            <p class="overlay-msg"></p>
            <i class="icon-spinner icon-spin icon-2x"></i>
            </div>
            
    </div>
    
       <!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               提示
            </h4>
         </div>
         <div class="modal-body">
            	提示内容
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default"  id="modal-close"
               data-dismiss="modal">关闭
            </button>
         
         </div>
      </div><!-- /.modal-content -->
</div>
		</div><!-- /.modal -->
    
    <footer class="footer">
        <div class="container">
     
        <p class="pull-right">E拍网管理后台</p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
