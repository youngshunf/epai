<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->mobile;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel-white">

    <h5><?= Html::encode($this->title) ?></h5>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除此用户?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'nick',
            'mobile',
            'email',
            ['attribute'=>'sex','value'=>CommonUtil::getDescByValue('user', 'sex', $model->sex)],
            'province',
            'city',
            'country',
              ['attribute'=>'created_at','value'=>CommonUtil::fomatTime($model->created_at)],
              'last_ip',
              ['attribute'=>'last_time','value'=>CommonUtil::fomatTime($model->last_time)],
     
        ],
    ]) ?>

</div>
