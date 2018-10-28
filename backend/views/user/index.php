<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户管理';
$this->params['breadcrumbs'][] = $this->title;
?>

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   <button class="btn btn-success" id="sendCheck">发放优惠券(选中用户)</button>
	<button class="btn btn-primary" id="sendAll">发放优惠券(全部用户)</button>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
        'pager'=>[
            'firstPageLabel'=>'首页',
            'lastPageLabel'=>'尾页'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'序号'],
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'id',
            ],
            'mobile',
            'nick',
             'province',
             'city',
            
                [	'class' => 'yii\grid\ActionColumn',
             	'header'=>'操作',
             	'options'=>['width'=>'200px'],
            	'template'=>'{view}{update}{delete}{reset-password}{ban-user}',
	             'buttons'=>[
					'view'=>function ($url,$model,$key){
	                     return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '查看详细'] );
					},
					'update'=>function ($url,$model,$key){
					
					       return  Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => '修改用户'] );					       												   
				},
					'delete'=>function ($url,$model,$key){
					return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => '删除用户', 'data' => [
                                        'confirm' => '您确定要删除此用户?',
                                        'method' => 'post',
                                    ],] );
					},
					'reset-password'=>function ($url,$model,$key){
					return  Html::a(' 重置密码 | ', $url, ['title' => '重置密码', 'data' => [
					    'confirm' => '您确定重置此用户的密码吗?',
					    'method' => 'post',
					],] );
					},
					'ban-user'=>function ($url,$model,$key){
					if($model->status==1){
					    return  Html::a(' 禁止拍卖', $url, ['title' => '禁止拍卖',] );
					}elseif($model->status==0){
					    return  Html::a(' 允许拍卖', $url, ['title' => '允许拍卖',] );
					}
					
					},
				]
           	],
        ],
    ]); ?>
    
    <!-- 发送优惠券 -->
<div class="modal fade" id="dataModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
               发放优惠券
            </h4>
         </div>
         <div class="modal-body center">
            	<form action="sendcoupon" method="post" onsubmit="return check()" >
            	<input type="hidden" name="sendtype" id="sendtype">
            	<input type="hidden" name="keys" id="keys">
            	<input type="hidden" name="_csrf" value="<?= yii::$app->request->getCsrfToken()?> ">
        			<div class="form-group">
        			<label>优惠券金额(元)</label>
        			<input class="form-control" type="number" name="amount">
        			</div>
        			<div class="form-group">
        			<label>过期时间</label>
        			<input class="form-control" type="date" name="end_time">
        			</div>
        			<div class="form-group">
        			<label>使用门槛(元)</label>
        			<input class="form-control" type="number" name="min_amount">
        			</div>
        			<div class="form-group">
        			<label>备注</label>
        			<input class="form-control" type="text" name="remark">
        			</div>
        			<button type="submit" id="send-link-do"  class="btn btn-primary" >发放</button>
        	</form>	
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default"  id="modal-close"
               data-dismiss="modal">关闭
            </button>
         
         </div>
      </div><!-- /.modal-content -->
</div>
</div><!-- /.modal -->
    
<script type="text/javascript">
var sendType='all';
var keys=[];
$('#sendCheck').click(function(){
	sendType=1;
	keys= $("#grid").yiiGridView("getSelectedRows");
	if(keys.length<=0){
	      modalMsg('请至少选择一项');
	      return false;
	}
	$('#sendtype').val(sendType);
	$('#keys').val(keys);
	 $('#dataModal').modal('show');
})

$('#sendAll').click(function(){
	sendType=2;
	$('#sendtype').val(sendType);
	 $('#dataModal').modal('show');
})

function check(){
  var amount=$('input[name=amount]').val();
  if(!amount){
	  modalMsg('请输入优惠券金额');
      return false;
  }
  var endTime=$('input[name=end_time]').val();
  if(!endTime){
	  modalMsg('请输入过期时间');
      return false;
  }
  
  return true;
	
}


</script>
