<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $model common\models\AuctionGoods */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'E拍宝商城', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel-white">
    <h5><?= Html::encode($this->title) ?></h5>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此项目吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="row">
    <div class="col-md-6">
   <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo?>" class="img-responsive">
  </div>
  <div class="col-md-6">
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'price',
            'number',
            'count_sales',
            'count_view',
            ['attribute'=>'end_time','value'=>CommonUtil::fomatTime($model->end_time)],
            ['attribute'=>'发布时间','value'=>CommonUtil::fomatTime($model->created_at)],
            ['attribute'=>'更新时间','value'=>CommonUtil::fomatTime($model->updated_at)],
        ],
    ]) ?>
    </div>
     <div class="col-lg-12">
   <h5>商品描述</h5>
  <?= $model->desc?>
  </div>
</div>
</div>