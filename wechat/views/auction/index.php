<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUtil;
use yii\widgets\ListView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchAuctionGoods */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '天天易拍';
?>
<div class="panel-white">

     <div class="control">
					<a class="item <?php if($status==0) echo "active";?>" href="<?= Url::to(['index','status'=>'0'])?>">
				正在拍卖
			</a>
				<a class="item  <?php if($status==1) echo "active";?>" href="<?= Url::to(['index','status'=>'1'])?>">
				即将开始
			</a>
					<a class="item <?php if($status==2) echo "active";?>" href="<?= Url::to(['index','status'=>'2'])?>">
				往期拍卖
			</a>		
   </div>
   <form action="<?= Url::to(['auction/search-do'])?>" method="post"  id="search-form">
     <div class="input-group" style="margin-top: 2px">
               <input type="text" name="keywords"  id="keywords" class="form-control" placeholder="输入关键词搜索" 
               <?php if(!empty($keywords)) echo "value='$keywords'";?> 
               >
                <input type="hidden"  name="cateid"  value="<?= $cateid?>">
                 <input type="hidden"  name="status"  value="<?= $status?>">
            <a href="javascript:;"   class="input-group-addon btn-success"  id="search">搜索</a>
        </div>
      </form>
</div> 
<div class="row">
    <?= ListView::widget([
            'dataProvider'=>$dataProvider,
            'itemView'=>'_item',            
           'layout'=>"{items}\n{pager}"
      ])?>

</div>

<script>
$('#search').click(function(){
    if(!$('#keywords').val()){
        modalMsg('请输入关键词搜索');
        return;
    }

    showWaiting('正在搜索,请稍候...');
    $('#search-form').submit();

});

  </script>
