<?php

use yii\widgets\ListView;
use yii\helpers\Url;
use common\models\CommonUtil;


$this->title = '卖家中心-'.$model->nick;
$this->params['breadcrumbs'][] = $this->title;
?>
 <div class="panel-white">
 <h5>卖家信息</h5>
<ul class="mui-table-view">
				
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
				<li class="mui-table-view-cell">
					<p><span class="bold">身份证号</span> <span class="pull-right"><?= $model->id_code?></span></p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">家庭地址</span> <span class="pull-right"><?= $model->home_address?></span></p>
				</li>
				<li class="mui-table-view-cell">
					<p><span class="bold">公司地址</span> <span class="pull-right"><?= $model->company_address?></span></p>
				</li>
				<li class="mui-table-view-cell">
				<p>亲，欢迎你成为易拍宝的拍友，“小e”见到你真的是太高兴了。</p>
				<p>你可以在“卖家中心”——“专场管理”——“发布专场”后建立您自己的专场拍卖，在您建立专场拍卖后，您可以在您自己的专场里发布拍品。快快行动吧，把您自己的拍品上拍，美美的赚钱，美美的享受拍卖的乐趣吧。</p>
				</li>
</ul>


   
</div>

