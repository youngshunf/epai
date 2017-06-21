<?php

use yii\widgets\ListView;
use yii\helpers\Url;
use common\models\CommonUtil;
use yii\helpers\Html;
use common\models\VipRefund;


/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchWish */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '个人中心-'.$model->nick;
?>


    <ul class="mui-table-view">
        
        <li class="mui-table-view-cell mui-media">				
					<?php if(empty($model->img_path)){?>
						<img class="mui-media-object mui-pull-left"  src="<?= yii::getAlias('@avatar')?>/unknown.jpg" >
						<?php }else{?>
						<img class="mui-media-object mui-pull-left"  src="<?= $model->img_path?>" >
						<?php }?>
						<div class="mui-media-body">
							       	<p class="bold"><?= !empty($model->nick)?$model->nick:CommonUtil::truncateMobile($model->mobile)?></p>
                                    <p><span class="mui-badge mui-badge-primary"><?= CommonUtil::getDescByValue('user', 'role_id', $model->role_id)?></span>
                                 </p>
                                 
						</div>
				
				</li>

    </ul>
  <ul class="mui-table-view">
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['my-profile'])?>">
					<span class="icon-user"></span>	个人信息
					</a>
				</li>
				<!-- 
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['my-coupon'])?>">
					<span class="icon-bookmark"></span>	我的优惠券
					</a>
				</li>
				 -->
					<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['my-order'])?>">
					<i class=" icon-reorder"></i>	我的订单
					<?php if($unPayOrder!=0){?>
					<span class="mui-badge mui-badge-danger"><?= $unPayOrder?></span>
					<?php }?>
					</a>
				</li>
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['my-auction'])?>">
					<img src="<?= yii::getAlias('@web')?>/fonts/iconfont-auction.png">	我的拍卖
					</a>
				</li>
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['my-love'])?>">
					<span class="icon-heart"></span>	我的收藏
					</a>
				</li>
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['my-address'])?>">
					<i class="icon-building"></i>	收货地址
					</a>
				</li>
				<!-- 
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['my-lottery'])?>">
					<img src="<?= yii::getAlias('@web')?>/fonts/iconfont-hxtreasure.png">	我的夺宝
					</a>
				</li>
				 -->
					<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['sys-message'])?>">
					<i class=" icon-envelope"></i>	系统通知
					<?php if($unread!=0){?>
					<span class="mui-badge mui-badge-danger"><?= $unread?></span>
					<?php }?>
					</a>
				</li>
				<!--  
				<?php if($model->role_id==3){
				$vipRefund=VipRefund::findOne(['user_guid'=>$model->user_guid,'status'=>0]);
				if(empty($vipRefund)){
				    ?>
				<li class="mui-table-view-cell ">
					<p class="center red">
					<?= Html::a('申请退还VIP保证金',['refund-vip'],['data-confirm'=>'您确定要申请退还VIP保证金吗？保证金退还后自动降级为普通用户,每次拍卖均须缴纳保证金!'])?>
					</p>
				</li>
				<?php } }?>
		      -->
</ul>


