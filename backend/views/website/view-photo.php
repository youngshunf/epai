<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\HomePhoto */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '首页图片', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="home-photo-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('修改', ['update-photo', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete-photo', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除此项目?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

   <img alt="<?= Html::encode($this->title) ?>" src="<?= yii::getAlias('@photo').'/'.$model->path.$model->photo?>" class="img-responsive">
    
    <?php if($model->type==0){?>
    <p>跳转连接:<a href="<?= $model->url?>"><?= $model->url?></a></p>
    <?php }else{?>
    <h5>自定义内容</h5>
    <?= $model->desc?>
    <?php }?>
</div>
