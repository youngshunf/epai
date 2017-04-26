<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchLotteryGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '一元夺宝';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel-white">

     <div  class="control">
					<a class="item <?php if($status==0) echo "active";?>" href="<?= Url::to(['index'])?>">
				正在进行
			</a>
				<a class="item  <?php if($status==1) echo "active";?>" href="<?= Url::to(['index','status'=>'1'])?>">
				已结束
			</a>
					<a class="item <?php if($status==2) echo "active";?>" href="<?= Url::to(['index','status'=>'2'])?>">
				已揭晓
			</a>		
				</div>
</div> 

  <div class="row">
    <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_item',            
           'layout'=>"{items}\n{pager}"
      ])?>

</div>

