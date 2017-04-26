<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '资讯分类', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('修改', ['update-cate', 'id' => $model->cateid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete-cate', 'id' => $model->cateid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此项目?',
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
        ['attribute'=>'创建者','value'=>$model->user->name],
      
            'name',
            'desc',
           ['attribute'=>'创建时间','value'=>CommonUtil::fomatTime($model->created_at)],
            ['attribute'=>'更新时间','value'=>CommonUtil::fomatTime($model->updated_at)],
        ],
    ]) ?>
 </div>

</div>

