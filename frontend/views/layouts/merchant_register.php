<?php 


use yii\widgets\Breadcrumbs;

use yii\helpers\Url;
use common\models\CommonUtil;
use common\models\User;

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
            
                
			<div class="col-lg-12  col-md-12">
		
        <?= $content ?>
        
    		
    		
    		
    		</div>
    		
            </div>
      
         </div>
<?php $this->endContent();?>
