<?php
use wechat\assets\AppAsset;
use yii\helpers\Html;
use frontend\widgets\Alert;
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
  
        <div class="wrap">
        <div class="main-content">      
         <?= Alert::widget() ?>
        <?= $content ?>
        
        	<nav class="mui-bar mui-bar-tab ">
			<a class="mui-tab-item" href="#Popover_0">易拍拍卖</a>
			<a class="mui-tab-item" href="#Popover_1">文玩天下</a>
			<a class="mui-tab-item" href="#Popover_2">E拍宝</a>
		</nav>
		<div id="Popover_0" class="mui-popover mui-bar-popover">
			<div class="mui-popover-arrow"></div>
			<ul class="mui-table-view">
			<li class="mui-table-view-cell"><a href="<?= Url::to(['lottery/index'])?>">一元夺宝</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['auction/round'])?>">专场拍卖</a>
				</li>
				<!--  <li class="mui-table-view-cell"><a href="<?= Url::to(['auction/personal-round'])?>">个人专场</a>
				</li>-->
				<li class="mui-table-view-cell"><a href="<?= Url::to(['auction/index'])?>">天天易拍</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['auction/index','status'=>1])?>">拍品预展</a>
				</li>
				
				<li class="mui-table-view-cell"><a href="<?= Url::to(['auction/cate'])?>">拍品分类</a>
				</li>
				
			</ul>
		</div>
		<div id="Popover_1" class="mui-popover mui-bar-popover">
			<div class="mui-popover-arrow"></div>
			<ul class="mui-table-view">
				<li class="mui-table-view-cell"><a href="<?= Url::to(['news/index'])?>">今日潘家园</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['treasure/index'])?>">易宝天下</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['knowledge/index'])?>">知文玩</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['mall/index'])?>">E拍商城</a>
				</li>
			</ul>
		</div>
		<div id="Popover_2" class="mui-popover mui-bar-popover">
			<div class="mui-popover-arrow"></div>
			<ul class="mui-table-view">
				<li class="mui-table-view-cell"><a href="<?= Url::to(['user/index'])?>">个人中心</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['merchant/index'])?>">卖家中心</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['site/contact'])?>">联系我们</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['site/mortage'])?>">网上典当</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['site/collect'])?>">拍品征集</a>
				</li>
			</ul>
		</div>
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
        <p >Copyright  &copy;  <?= date('Y')?> 北京易拍宝网络科技有限公司   </p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
