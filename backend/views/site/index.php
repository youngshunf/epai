<?php
use common\models\CommonUtil;
/* @var $this yii\web\View */

$this->title = '易拍宝管理后台';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
    
         
        <div class="col-lg-4">
        <div class="panel-white">
                <h2>网站管理</h2>

                <p>管理网站信息</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('website/index')?>">网站管理 &raquo;</a></p>
            </div>
            </div>
         
            <div class="col-lg-4">
            <div class="panel-white">
                <h2>用户管理</h2>

                <p>用户信息查看和删除操作</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('user/index')?>">用户管理 &raquo;</a></p>
            </div>
            </div>
           
       <div class="col-lg-4">
       <div class="panel-white">
                <h2>拍品管理</h2>

                <p>管理和发布拍品</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('auction/index')?>">拍品管理&raquo;</a></p>
            </div>
         </div>
        
        <div class="col-lg-4">
       <div class="panel-white">
                <h2>一元夺宝</h2>

                <p>管理和发布一元夺宝</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('lottery/index')?>">一元夺宝&raquo;</a></p>
            </div>
         </div>
            
               <div class="col-lg-4">
       <div class="panel-white">
                <h2>E拍商城</h2>

                <p>管理和发布商城商品</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('mall/index')?>">E拍商城&raquo;</a></p>
            </div>
         </div>
         
                <div class="col-lg-4">
       <div class="panel-white">
                <h2>资讯管理</h2>

                <p>管理和发布资讯</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('news/index')?>">资讯管理&raquo;</a></p>
            </div>
         </div>
            
                  <div class="col-lg-4">
       <div class="panel-white">
                <h2>订单管理</h2>

                <p>管理订单,包括拍卖订单、夺宝订单、商城订单</p>

                <p><a class="btn btn-primary" href="<?= yii::$app->urlManager->createUrl('order/index')?>">订单管理&raquo;</a></p>
            </div>
         </div>
            
        </div>

    </div>
</div>
