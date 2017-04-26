<?php

use yii\widgets\ListView;
use yii\helpers\Url;
use common\models\CommonUtil;


$this->title = "卖家须知";
$this->params['breadcrumbs'][] =['label' => '卖家中心', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
 <div class="panel-white">
 <h5>卖家须知</h5>
<ul class="mui-table-view">
				
				<li class="mui-table-view-cell">
				<p><span class="bold ">1、普通卖家</span>
					</p>
					<p>1) 普通卖家可以发布1个专题，每个专题不能超过8个拍品</p>
					<p>2) 普通卖家系统自动提取“真实”成交价的20%作为佣金</p>
				</li>
					<li class="mui-table-view-cell">
				<p><span class="bold ">1、高级卖家</span></p>
						<p>1) 高级卖家可以发布2个专题，每个专题不能超过10个拍品</p>
					<p>2) 高级卖家系统自动提取“真实”成交价的8%作为佣金</p>
					<p>3) 高级卖家必须保证所有拍品保真，并包邮。</p>
					<p>4)高级卖家需提交200元拍卖保证金，如不再参与拍卖活动，经系统审核后款项可以退还。</p>
				</li>
				

</ul>

</div>

