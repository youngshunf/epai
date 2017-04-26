<?php

use yii\widgets\ListView;
use yii\helpers\Url;
use common\models\CommonUtil;


$this->title = "实名认证中，请等待审核结果";
$this->params['breadcrumbs'][] =['label' => '卖家中心', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
 <div class="panel-white">
 <h5>实名认证中，请等待审核</h5>
<ul class="mui-table-view">
				
				<li class="mui-table-view-cell">
					<p><span class="bold ">您已提交实名认证资料，请等待审核结果,认证通过后将会为您开通卖家中心!</span>
					
					</p>
				</li>
				

</ul>

</div>

