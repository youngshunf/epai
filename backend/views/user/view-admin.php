<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\CommonUtil;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <h5><?= Html::encode($this->title) ?></h5>

    <p>
        <?= Html::a('修改', ['update-admin', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'name',
            'nick',
              ['attribute'=>'created_at','value'=>CommonUtil::fomatTime($model->created_at)],
              'last_ip',
              ['attribute'=>'last_time','value'=>CommonUtil::fomatTime($model->last_time)],
      
     
        ],
    ]) ?>

</div>
