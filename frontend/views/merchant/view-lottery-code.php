<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;
use common\models\LotteryRec;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchLotteryGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '查看夺宝号码';
$this->params['breadcrumbs'][] = ['label' => '个人中心', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

  <ul class="mui-table-view">
				<li class="mui-table-view-cell mui-media">			
					<img class="mui-media-object mui-pull-left"  src="<?= yii::getAlias('@photo').'/'.$goods->path.'thumb/'.$goods->photo?>" >						
						<div class="mui-media-body">
							<p><?= $goods->name?></p>
							<p>总需: <?= $goods->price?></p>
							<p>本次参与: <span class="green"><?= LotteryRec::find()->andWhere(['user_guid'=>yii::$app->user->identity->user_guid,'goods_guid'=>$goods->goods_guid])->count()?></span>人次														
						</div>
				
				</li>
    </ul>

<div class="panel-white">
<h5>抽奖号码</h5>
    <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_lottery_code_item',            
           'layout'=>"{items}\n{pager}"
      ])?>
</div>


