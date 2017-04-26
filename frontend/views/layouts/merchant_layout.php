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
<?php $this->beginContent('@app/views/layouts/merchant_main.php');?>
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
                                    <p><span class="mui-badge mui-badge-success"><?= CommonUtil::getDescByValue('user', 'merchant_role', $model->merchant_role)?></span>
                                    </p>
                                    <br>
                                  <p><a class="mui-btn mui-btn-warning" href="<?= Url::to(['merchant-upgrade'])?>">升级为高级卖家</a></p>
                               
                                 
						</div>
				
				</li>

    </ul>
  <ul class="mui-table-view">
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['index'])?>">
					<span class="icon-user"></span>	卖家信息
					</a>
				</li>
					<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['order'])?>">
					<i class=" icon-reorder"></i>	订单管理
					</a>
				</li>
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['round'])?>">
					<i class=" icon-th-large"></i>	专场管理
					</a>
				</li>
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['goods'])?>">
					<i class=" icon-briefcase"></i>	拍品管理
					</a>
				</li>
					<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['merchant-message'])?>">
					<i class=" icon-envelope"></i>	系统通知
					<?php 
					$unread=Message::find()->andWhere(['to_user'=>$user_guid,'type'=>Message::MERCHANT,'is_read'=>0])->count();
					if($unread!=0){?>
					<span class="mui-badge mui-badge-danger"><?= $unread?></span>
					<?php }?>
					</a>
				</li>
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right"  href="<?= Url::to(['merchant-note'])?>">
					<i class="  icon-bookmark"></i>	卖家须知
					</a>
				</li>
		
</ul>
                     
                </div>
                </div>
                
			<div class="col-lg-9  col-md-9">
		<div class="panel-white">
        <?= $content ?>
        
    		
    		
    		
    		</div>
    		
            </div>
      
         </div>
      </div>
<?php $this->endContent();?>
