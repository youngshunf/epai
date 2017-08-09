<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '资讯分类', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->newsid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->newsid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此项目?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a($model->is_recommend==0?'推荐':'取消推荐', ['recommend', 'id' => $model->newsid], ['class' => 'btn btn-danger']) ?>
    </p>
    <div class="row">
  <div class="col-md-6">
   <img alt="封面图片" src="<?= yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo?>" class="img-responsive">
  </div>
  <div class="col-md-6">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        ['attribute'=>'发布者','value'=>$model->user->name],      
            'title',
            ['attribute'=>'是否推荐','value'=>$model->is_recommend==0?'否':'是'],
            ['attribute'=>'是否发布到网站','value'=>$model->is_web==0?'否':'是'],
            ['attribute'=>'是否发布到微信','value'=>$model->is_wechat==0?'否':'是'],
           ['attribute'=>'发布时间','value'=>CommonUtil::fomatTime($model->created_at)],
            ['attribute'=>'更新时间','value'=>CommonUtil::fomatTime($model->updated_at)],
        ],
    ]) ?>
 </div>
   <div class="col-lg-12">
   <h5>内容</h5>
  <?= $model->content?>
  </div>
</div>

