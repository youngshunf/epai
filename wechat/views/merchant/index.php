<?php

use yii\helpers\Url;
use common\models\CommonUtil;
use common\models\Message;


$this->title = '卖家中心-'.$model->name;
?>
<div class="row">
              <div class="col-xs-12 "> 
                <div class="panel-white">
               <ul class="mui-table-view">
        
        <li class="mui-table-view-cell mui-media">				
					<?php if(empty($model->img_path)){?>
						<img class="mui-media-object mui-pull-left"  src="<?= yii::getAlias('@avatar')?>/unknown.jpg" >
						<?php }else{?>
						<img class="mui-media-object mui-pull-left"  src="<?= $model->img_path?>" >
						<?php }?>
						<div class="mui-media-body">
							       	<p class="bold"><?= !empty($model->name)?$model->name:CommonUtil::truncateMobile($model->mobile)?></p>
                                    <p><span class="mui-badge mui-badge-success"><?= CommonUtil::getDescByValue('user', 'merchant_role', $model->merchant_role)?></span>
                                    </p>
                                    <br>
                                  <p><a class="mui-btn mui-btn-warning" href="<?= Url::to(['merchant-upgrade'])?>">升级为高级卖家</a></p>
                               
                                 
						</div>
				
				</li>

    </ul>
  <ul class="mui-table-view">
				<li class="mui-table-view-cell">
					<a class="mui-navigate-right" href="<?= Url::to(['merchant-profile'])?>">
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
					$unread=Message::find()->andWhere(['to_user'=>$model->user_guid,'type'=>Message::MERCHANT,'is_read'=>0])->count();
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
</div>

