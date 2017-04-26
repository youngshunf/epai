<?php

use yii\widgets\ListView;
use yii\helpers\Url;
use common\models\CommonUtil;


$this->title = "卖家注册";
$this->params['breadcrumbs'][] =['label' => '卖家中心', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
 <div class="panel-white">
 <h5>卖家中心</h5>
<ul class="mui-table-view">
				
				<li class="mui-table-view-cell">
					<p><span class="bold red">您还不是卖家,要成为卖家需要先填写您的个人信息和验证身份。请填写以下信息提交审核:</span>
					
					</p>
				</li>
					<li class="mui-table-view-cell">
					<?= $this->render('_merchant_form', [
                        'model' => $model,
                    ]) ?>
					</li>
				

</ul>

</div>

