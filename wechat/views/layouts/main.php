<?php
use wechat\assets\AppAsset;
use yii\helpers\Html;
use frontend\widgets\Alert;
use yii\helpers\Url;
use common\models\JSSDK;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$description = empty($description)?yii::$app->params['site-desc']:$description;
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
	 <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
</head>
<body>
    <?php $this->beginBody() ?>
    
    <div class="wrap">   
  
        <div class="wrap">
        <div class="main-content">      
         <?= Alert::widget() ?>
        <?= $content ?>
        
        	<nav class="mui-bar mui-bar-tab ">
			<a class="mui-tab-item" href="#Popover_0">我要拍</a>
			<a class="mui-tab-item" href="#Popover_1">我要学习</a>
			<a class="mui-tab-item" href="#Popover_2">联系我们</a>
		</nav>
		<div id="Popover_0" class="mui-popover mui-bar-popover">
			<div class="mui-popover-arrow"></div>
			<ul class="mui-table-view">
			    
				<li class="mui-table-view-cell"><a href="<?= Url::to(['auction/round'])?>">进入拍场</a>
				</li>
				<li class="mui-table-view-cell"><a href="https://weidian.com/?userid=257822600">进入微店</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['user/index'])?>">个人中心</a>
				</li>
				<!--  <li class="mui-table-view-cell"><a href="<?= Url::to(['auction/personal-round'])?>">进入拍场</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['auction/index'])?>">天天易拍</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['auction/index','status'=>1])?>">拍品预展</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['lottery/index'])?>">一元夺宝</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['auction/cate'])?>">拍品分类</a>
				</li>
				-->
				
			</ul>
		</div>
		<div id="Popover_1" class="mui-popover mui-bar-popover">
			<div class="mui-popover-arrow"></div>
			<ul class="mui-table-view">
				<li class="mui-table-view-cell"><a href="<?= Url::to(['news/index'])?>">我要学习</a>
				</li>
				<li class="mui-table-view-cell"><a href="https://mp.weixin.qq.com/s?__biz=MzIxOTE0ODQ3Mg==&mid=2651610666&idx=1&sn=2b4aee9bb12c1895d0bb6f888ae090b9&chksm=8c2747fdbb50ceeb47602fb09b00591719cb1b6e38a695f25b90688727e76b1764361a401317&mpshare=1&scene=1&srcid=0614SBggCyU7JpUgeVekHj0Q&key=ba1edc5f4b6bb31c7642c30e48eb75d7b15f44e45b813bedc2e41605904cb9f65060dc05f0d7f3e5a0947cae609998cec5b25b8c71d80abbf7727b522ba1635a517b03b2cee1c4b1ba1a90ce716253a3&ascene=0&uin=MjQwMDQwMjU4MA%3D%3D&devicetype=iMac+MacBookPro12%2C1+OSX+OSX+10.12.5+build(16F73)&version=12020710&nettype=WIFI&fontScale=100&pass_ticket=UnTCy2VYtm%2Ff6CMNhTs1mP%2BWdb4ltXAXTKDT5ZKd3lG7K7IXk3EMW9ysFI3tcBjY">
				手秀大赛</a>
				</li>
				<!--  
				<li class="mui-table-view-cell"><a href="<?= Url::to(['treasure/index'])?>">易宝天下</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['knowledge/index'])?>">知文玩</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['mall/index'])?>">E拍商城</a>
				</li>
				-->
			</ul>
		</div>
		<div id="Popover_2" class="mui-popover mui-bar-popover">
			<div class="mui-popover-arrow"></div>
			<ul class="mui-table-view">
				<!--  
				<li class="mui-table-view-cell"><a href="<?= Url::to(['merchant/index'])?>">卖家中心</a>
				</li>
				-->
				<li class="mui-table-view-cell"><a href="<?= Url::to(['site/mortage'])?>">拍卖合同</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['site/collect'])?>">拍品征集</a>
				</li>
				<li class="mui-table-view-cell"><a href="<?= Url::to(['site/contact'])?>">联系客服</a>
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
        <p >Copyright  &copy;  <?= date('Y')?> 小火文玩拍卖   </p>
        </div>
    </footer>
<script type="text/javascript">
<?php 
$jssdk=new JSSDK(yii::$app->params['appid'], yii::$app->params['appsecret']);
$signPackage = $jssdk->GetSignPackage();
// $picUrl=empty($picUrl)?yii::$app->params['picUrl']:$picUrl;
?>

wx.config({  
    debug: false,  
    appId: '<?= $signPackage["appId"]?>',  
    timestamp: '<?= $signPackage["timestamp"]?>',  
    nonceStr: '<?=$signPackage["nonceStr"]?>',  
    signature: '<?= $signPackage["signature"]?>',  
    jsApiList: [  
      // 所有要调用的 API 都要加到这个列表中  
        'checkJsApi',  
        'onMenuShareTimeline',  
        'onMenuShareAppMessage',  
        'onMenuShareQQ',  
        'onMenuShareWeibo',  
        'onMenuShareQZone'  
    ]  
  }); 

</script>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
