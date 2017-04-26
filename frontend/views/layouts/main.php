<?php
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\models\CommonUtil;
use frontend\assets\FlatlabAsset;
use common\models\Message;
use frontend\widgets\Alert;
use common\models\Wallet;
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
   	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>  
           <?php if(isset($keywords)){?>
        <meta name="keywords" content="<?=$keywords?>" /> 
        <?php }else{?>
        <meta name="keywords" content="<?= yii::$app->params['site-keywords'] ?>" /> 
        <?php }?>
        <?php if(isset($description)){?>
         <meta name="description" content="<?= $description?>" /> 
       <?php }else{?>
         <meta name="description" content="<?= yii::$app->params['site-desc'] ?>" />
       <?php }?>
	 <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    
    <div class="wrap">   
     <div class="top-bar top-fix hidden-xs">
      <div class="container">
        <span class="top-divider ">
        <a href=<?= Url::to(['site/index'])?>>易拍宝首页</a>
        </span>
        <span class="top-divider ">
       欢迎您!
        <?php if(yii::$app->user->isGuest){?>
        <a href=<?= Url::to(['site/login'])?> >请登录</a> | <a href=<?= Url::to(['site/register'])?>>注册</a>
        <?php }else{?>
        <span><?= CommonUtil::getLoginUser()?></span> |
        <a href=" <?= Url::to(['user/index'])?> ">个人中心</a> |
        <a href=" <?= Url::to(['merchant/index'])?> ">卖家中心</a> |
        <?= Html::a('退出登录',['site/logout'])?>
        <?php }?>
        
        </span>
      
       
      </div>
     </div>
     <div class="search-bar hidden-xs">
      <div class="container">
        <div class="row">
   
        <div class="col-md-6  col-sm-6">
             <div class="top-info">
        <img alt="logo" src="<?= yii::getAlias('@web')?>/img/logo.png" class="logo" >
   <!--      <span>www.1paibao.cn</span> -->
        <span>专业的网络拍卖平台</span>
        </div>
        </div>
        <div class="col-md-6 col-sm-6">
         <div class="top-info">
        <div class="form-group">
       
            <div class="input-group" style="margin-top: 20px">
            <a href="javascript:;"   class="dropdown-toggle input-group-addon" id="dropdownMenu1" 
                  data-toggle="dropdown">
                  拍品
                  <span class="caret"></span>
               </a>
             <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                  <li role="presentation">
                     <a role="menuitem" tabindex="-1" href="#">资讯</a>
                  </li>
               </ul>
               <input type="text" name="keywords" class="form-control" placeholder="输入关键词搜索">
                <input type="hidden" name="type" >
            <a href="javascript:;"   class="input-group-addon btn-info">搜索</a>
          </div>
          </div>
              
        </div>      
         </div>     
       </div>
       </div>
       </div>
       <div class="wrap">
       <div class="row">  
         <div class="col-md-12">
          <?php
            NavBar::begin([
                'brandLabel' => '易拍宝',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse ',
                ],
            ]);
                   
				$menuItems[] = [
					'label' => '首页', 
					'url' => ['/site/index'],
				    'active'=>yii::$app->controller->id=='site'&&yii::$app->controller->action->id=='index'
				];
				$menuItems[] = [
					'label' => '新闻资讯',
					 'url' => ['/news/index'],
				    'active'=>yii::$app->controller->id=='news'
				];
				$menuItems[] = [
				    'label' => '天天易拍',
				    'url' => ['/auction/index'],
				    'active'=>yii::$app->controller->id=='auction'&&yii::$app->controller->action->id=='index'
				];
				$menuItems[] = [
				    'label' => '专场拍卖',
				    'url' => ['/auction/round'],
				    'active'=>yii::$app->controller->id=='auction'&&yii::$app->controller->action->id=='round'
				];
// 				$menuItems[] = [
// 				    'label' => '个人专场',
// 				    'url' => ['/auction/personal-round'],
// 				    'active'=>yii::$app->controller->id=='auction'&&yii::$app->controller->action->id=='personal-round'
// 				];
				$menuItems[] = [
				    'label' => '拍品预展',
				    'url' => ['/auction/preview'],
				    'active'=>yii::$app->controller->id=='auction'&&yii::$app->controller->action->id=='preview'
				];		
				$menuItems[] = [
				    'label' => '拍品征集',
				    'url' => ['/site/collect'],
				    'active'=>yii::$app->controller->id=='site'&&yii::$app->controller->action->id=='collect'
				];
				$menuItems[] = [
				    'label' => '易宝天下',
				    'url' => ['/treasure/index'],
				    'active'=>yii::$app->controller->id=='treasure'
				];
				$menuItems[] = [
				    'label' => '知文玩',
				    'url' => ['/knowledge/index'],
				    'active'=>yii::$app->controller->id=='knowledge'
				];
			
			
				$menuItems[] = [
				    'label' => '联系我们',
				    'url' => ['/site/contact'],
				    'active'=>yii::$app->controller->id=='site'&&yii::$app->controller->action->id=='contact'
				];
				    
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>
         </div>
                    
        </div>
        </div>
        
        <div class="wrap">
        <div class="main-content">      
         <?= Alert::widget() ?>
        <?= $content ?>
        </div>
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
        <p >Copyright  &copy;  <?= date('Y')?> 北京易拍宝网络科技有限公司  京ICP备16000285号-1 </p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
