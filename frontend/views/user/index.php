<?php

use yii\widgets\ListView;
use yii\helpers\Url;
use common\models\CommonUtil;


/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchWish */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '个人中心-'.$model->nick;
$this->params['breadcrumbs'][] = $this->title;
?>
 <div class="panel-white">
 <h5>个人信息</h5>
<ul class="mui-table-view">
				
				<li class="mui-table-view-cell">
					<p><span class="bold">昵称</span>
					<span class="pull-right"><?= $model->nick?></span>
					</p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">姓名</span>
					<span class="pull-right"><?= $model->name?></span>
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
    <div class="center">
   <a class="btn btn-primary  btn-lg" href="<?= Url::to(['update-profile'])?>">修改个人信息</a>
    </div>
    <?php }?>
   
</div>

