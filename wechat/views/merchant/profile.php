<?php
use common\models\CommonUtil;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchWish */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '个人信息-'.$model->nick;

?>

  <ul class="mui-table-view">
				<li class="mui-table-view-cell">
				<p><span class="bold" style="line-height: 80px">头像 </span>
				<?php if(empty($model->img_path)){?>
						<img class=" mui-pull-right img-responsive head-img"  src="<?= yii::getAlias('@avatar')?>/unknown.jpg">
						<?php }else{?>
						<img class=" mui-pull-right img-responsive head-img"  src="<?= $model->img_path?>" >
						<?php }?>
				</p>							
					
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">昵称</span>
					<span class="pull-right"><?= $model->nick?></span>
					</p>
				</li>
			<li class="mui-table-view-cell">
					<p><span class="bold">电话</span>
					<span class="pull-right"><?= $model->mobile?></span>
					</p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">性别</span> <span class="pull-right"><?= CommonUtil::getDescByValue('user', 'sex', $model->sex)?></span></p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">省份</span> <span class="pull-right"><?= $model->province?></span></p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">城市</span> <span class="pull-right"><?= $model->city?></span></p>
				</li>

</ul>


    <?php if(!yii::$app->user->isGuest&&yii::$app->user->identity->user_guid==$model->user_guid){?>
    <div class="wish-info">
   <!--  <a class="btn btn-primary btn-block btn-lg" href="<?= Url::to(['update-profile'])?>">修改个人信息</a> -->
    </div>
    <?php }?>

