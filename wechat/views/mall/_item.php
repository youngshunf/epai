<?php

use yii\helpers\Url;

?>

<a href="<?= Url::to(['view','id'=>$model['id']])?>">
        <div class="col-md-4">
            <ul class="auction">
			<li class="">
				<a href="<?= Url::to(['view','id'=>$model->id])?>">
				<div class="c_img">
					<img src="<?= yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo?>"  class="img-responsive">
			 <div class="c_words mui-ellipsis">
			    <?= $model->name?>
			 </div>
			</div>
				</a>				
				<div class="pai-item pai-content">
				 <p><i class="red-normal">￥<?= $model->price?></i>             	
					<a href="<?= Url::to(['view','id'=>$model->id])?>"  class="btn btn-danger bid-btn pull-right" >立即购买</a>
                 </p>		
                 <p class="clear"></p>								 				 
				</div>
				
				<div class="item-bid-box">
                    <span class="side-num">已卖出<?= $model->count_sales?></span>
				</div>
			
			</li>						
			</ul>				 				 
        </div>
</a>
<script type="text/javascript">

    $(".item-countdown").each(function(){
        var that=$(this);
        var countTime=$(this).attr('data-time');
        $(this).downCount({
    		date: countTime,
    		offset: +10
    	}, function () {
    	//	alert('倒计时结束!');
        	that.find('.bid-btn').removeClass('btn-danger');
        	that.find('.bid-btn').html('已结束');
    	});
    });    	
    

</script>
  
