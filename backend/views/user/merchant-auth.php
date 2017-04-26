<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\GuaranteeFee;
use common\models\CommonUtil;
use common\models\Order;
use common\models\IdPhoto;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '卖家身份审核';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/jquery.fancybox.css');
$this->registerCssFile('@web/js/jquery.fancybox.js');
?>

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            'user.mobile',
            'user.name',
            'user.id_code',
            ['attribute'=>'身份证正面照',
            'format'=>'html',
            'options'=>['width'=>'150px'],
            'value'=>function ($model){
            $idPhoto=IdPhoto::findOne(['user_guid'=>$model->user_guid,'type'=>1]);
                if(!empty($idPhoto)){
                  return  "<a class='fancybox'  title='身份证正面照'  data-fancybox-group='gallery'  href='".yii::getAlias('@photo').'/'.$idPhoto->path.$idPhoto->photo."'>".Html::img(yii::getAlias('@photo').'/'.$idPhoto->path.'thumb/'.$idPhoto->photo,['class'=>'img-responsive'])."</a>";
                }else{
                    return '无';
                }
            }],
            ['attribute'=>'身份证背面照',
            'format'=>'html',
            'options'=>['width'=>'150px'],
            'value'=>function ($model){
                $idPhoto=IdPhoto::findOne(['user_guid'=>$model->user_guid,'type'=>2]);
                if(!empty($idPhoto)){
                      return  "<a class='fancybox'  title='身份证背面照'  data-fancybox-group='gallery'  href='".yii::getAlias('@photo').'/'.$idPhoto->path.$idPhoto->photo."'>".Html::img(yii::getAlias('@photo').'/'.$idPhoto->path.'thumb/'.$idPhoto->photo,['class'=>'img-responsive'])."</a>";
                }else{
                    return '无';
                }
            }],
            ['attribute'=>'手持身份证照片',
            'format'=>'html',
            'options'=>['width'=>'150px'],
            'value'=>function ($model){
                $idPhoto=IdPhoto::findOne(['user_guid'=>$model->user_guid,'type'=>3]);
                if(!empty($idPhoto)){
                     return  "<a class='fancybox'  title='手持身份证照片'  data-fancybox-group='gallery'  href='".yii::getAlias('@photo').'/'.$idPhoto->path.$idPhoto->photo."'>".Html::img(yii::getAlias('@photo').'/'.$idPhoto->path.'thumb/'.$idPhoto->photo,['class'=>'img-responsive'])."</a>";
                }else{
                    return '无';
                }
            }],
            ['attribute'=>'状态',
            'format'=>'html',
            'value'=>function ($model){
               return CommonUtil::getDescByValue('user', 'merchant_apply_status', $model->user->merchant_apply_status);
            }],
         
             
             ['attribute'=>'申请时间',
             'value'=>function ($model){
              return CommonUtil::fomatTime($model->created_at);
             },
             
             ],
            
                [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
             	'options'=>['width'=>'100px'],
            	'template'=>'{merchant-auth-pass}{merchant-auth-deny}',
	             'buttons'=>[
					'merchant-auth-pass'=>function ($url,$model,$key){
					if(!empty($model->user)&&$model->user->merchant_apply_status==1){
					    return Html::a('审核通过 | ',$url);
					}
					},
					'merchant-auth-deny'=>function ($url,$model,$key){
					if(!empty($model->user)&&$model->user->merchant_apply_status==1){
					    return Html::a('审核不通过',$url);
					}
	                   
				},
				
				]
           	],
        ],
    ]); ?>

<script>
//点击查看大图
    $(document).ready(function(){
        $('.fancybox').fancybox({
        		closeClick:true,
        		padding : 0,
        		cyclic:true,			
        		overlayShow:true,
        		overlayColor : "#000000",
        		overlayOpacity: 0.8, 
        });
    });
</script>