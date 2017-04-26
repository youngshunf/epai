<?php 


use yii\widgets\Breadcrumbs;

use yii\helpers\Url;
use common\models\CommonUtil;
use common\models\User;
use common\models\Message;

$user_guid=yii::$app->user->identity->user_guid;
$model=User::findOne(['user_guid'=>$user_guid]);
$this->registerCssFile('@web/css/mui.min.css');
$this->registerCssFile('@web/css/mui-reset.css');
?>
<!-- 先引用main.php布局文件， -->
<?php $this->beginContent('@app/views/layouts/main.php');?>
    <div class="container ">
       <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
    <div class="row">
              <div class="col-lg-3 col-md-3"> 
                <div class="panel-white">
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
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['my-coupon'])?>">
					<span class="icon-bookmark"></span>	我的优惠券
					</a>
				</li>
					<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['my-order'])?>">
					<i class=" icon-reorder"></i>	我的订单
					</a>
				</li>
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['my-auction'])?>">
					<img src="<?= yii::getAlias('@web')?>/fonts/iconfont-auction.png">	我的拍卖
					</a>
				</li>
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['my-lottery'])?>">
					<img src="<?= yii::getAlias('@web')?>/fonts/iconfont-hxtreasure.png">	我的夺宝
					</a>
				</li>
					<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['sys-message'])?>">
					<i class=" icon-envelope"></i>	系统通知
					<?php 
					$unread=Message::find()->andWhere(['to_user'=>$user_guid,'type'=>Message::SYS,'is_read'=>0])->count();
					if($unread!=0){?>
					<span class="mui-badge mui-badge-danger"><?= $unread?></span>
					<?php }?>
					</a>
				</li>
		      <li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['sys-message'])?>">
					<i class=" icon-comment"></i>	我的私信
					<?php 
					$unread=Message::find()->andWhere(['to_user'=>$user_guid,'type'=>Message::USER,'is_read'=>0])->count();
					if($unread!=0){?>
					<span class="mui-badge mui-badge-danger"><?= $unread?></span>
					<?php }?>
					</a>
				</li>
</ul>
                     
                
             
                </div>
                </div>
                
			<div class="col-lg-9 col-md-9">
		
        <?= $content ?>
        
    		
    		
    		
    		</div>
    		
            </div>
      
         </div>
      </div>
<?php $this->endContent();?>
