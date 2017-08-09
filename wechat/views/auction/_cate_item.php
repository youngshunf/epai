<?php


use yii\helpers\Url;
$now=time();
?>

<a href="<?= Url::to(['index','cateid'=>$model['cateid']])?>">
        <div class="col-md-4">
            <ul class="auction">
			<li class="">
				<a href="<?= Url::to(['index','cateid'=>$model->cateid])?>">
				<div class="c_img">
					<img src="<?= yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo?>"  class="img-responsive">
				<div class="c_words">
				<?= $model->name?>
				</div>
				</div>
				</a>				
				
			
			</li>						
			</ul>				 				 
        </div>
</a>

  
