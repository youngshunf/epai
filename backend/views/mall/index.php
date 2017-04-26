<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchLotteryGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'E拍宝商城';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('发布宝贝', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'name',
              ['attribute'=>'desc','value'=>function ($model){
               return CommonUtil::cutHtml($model->desc, 50);
           }],
             'price',
               ['attribute'=>'创建时间','value'=>function ($model){
               return CommonUtil::fomatTime($model->created_at);
           }],

            ['class' => 'yii\grid\ActionColumn','header'=>'操作'],
        ],
    ]); ?>

</div>
