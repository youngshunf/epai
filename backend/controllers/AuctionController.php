<?php

namespace backend\Controllers;

use Yii;
use common\models\AuctionGoods;
use common\models\SearchAuctionGoods;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\AuctionCate;
use yii\data\ActiveDataProvider;
use common\models\ImageUploader;
use common\models\CommonUtil;
use yii\filters\AccessControl;
use common\models\AuctionRound;
use common\models\AuctionBidRec;
use common\models\GuaranteeFee;
use common\models\Message;
use common\models\User;

/**
 * AuctionController implements the CRUD actions for AuctionGoods model.
 */
class AuctionController extends Controller
{
    public $enableCsrfValidation = false;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    public function beforeAction($action){
        $this->layout="@backend/views/layouts/auction_layout.php";
        return parent::beforeAction($action);
    }

    /**
     * Lists all AuctionGoods models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect('round');
         $dataProvider = new ActiveDataProvider([
            'query'=>AuctionCate::find(),
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);
         
       
        return $this->render('index', [        
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionRound()
    {
        $dataProvider = new ActiveDataProvider([
            'query'=>AuctionRound::find()->andWhere(['post_type'=>1])->orderBy('sort desc,created_at desc'),
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);
    
        return $this->render('round', [
                'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionSendMessageOnline($id){
        $res=$this->SendMessage($id, 1);
        
        yii::$app->getSession()->setFlash('success',"发送成功,已通知 $res 个用户!");
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionSendMessageOngoing($id){
        $res=$this->SendMessage($id, 2);
        
        yii::$app->getSession()->setFlash('success',"发送成功,已通知 $res 个用户!");
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionSendMessageOffline($id){
        $res=$this->SendMessage($id, 3);
        yii::$app->getSession()->setFlash('success',"发送成功,已通知 $res 个用户!");
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function SendMessage($id,$type){
        $res=0;
        foreach (User::find()->each(100) as $user){
            if(CommonUtil::SendOnlineMessage($user->user_guid, $id,$type)){
                $res++;
            }
        }
        return $res;
    }
    
    public function actionRoundOrder($id){
        return $this->redirect(['order/index','roundid'=>$id]);
    }
    
    public function actionOffline($id){
        $round=AuctionRound::findOne($id);
        if($round->offline==1){
            $round->offline=0;
        }elseif($round->offline==0){
            $round->offline=1;
        }
        if($round->save()){
            yii::$app->getSession()->setFlash('success','操作成功!');
        }
        return $this->redirect(yii::$app->request->referrer);
    }
    public function actionGoods()
    {
        $searchModel = new SearchAuctionGoods();
        $searchModel->post_type=1;
        $cate="";
        if(isset($_GET['cateid'])){
            $cateid=$_GET['cateid'];
            $searchModel->cateid=$cateid;
            $cate=AuctionCate::findOne($cateid);
        }
      
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);   
        return $this->render('goods', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'cate'=>$cate
        ]);
    }
    
    public function actionOngoing()
    {
        $searchModel = new SearchAuctionGoods();
       $searchModel->status=1;
    
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('goods', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionManualCount(){
        $goods_guid=$_POST['goods_guid'];
        $count=$_POST['count'];
        $auctionGoods=AuctionGoods::findOne(['goods_guid'=>$goods_guid]);
        $auctionGoods->count_view+=$count;
        if($auctionGoods->save()){
            yii::$app->getSession()->setFlash('success','设置成功!');
            return $this->redirect(yii::$app->request->referrer);
        }
        
        yii::$app->getSession()->setFlash('error','设置失败!');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionAllGoods()
    {
        $searchModel = new SearchAuctionGoods();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->layout="@backend/views/layouts/auction_layout.php";
        return $this->render('goods', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
   
        ]);
    }

    /**
     * Displays a single AuctionGoods model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewGoods($id)
    {
        $this->layout="@backend/views/layouts/auction_layout.php";
        return $this->render('view-goods', [
            'model' => $this->findModel($id),
        ]);
    }
    
    public function actionGoodsGuarantee($id){
        $goods=AuctionGoods::findOne($id);
        $dataProvider=new ActiveDataProvider([
            'query'=>GuaranteeFee::find()->andWhere(['goods_guid'=>$goods->goods_guid,'user_role'=>2])->orderBy('created_at desc')
        ]);
        
        return $this->render('goods-guarantee',[
            'goods'=>$goods,
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionCreateCate()
    {
        $model = new AuctionCate();
    
        if ($model->load(Yii::$app->request->post()) ) {
            $model->user_guid=yii::$app->user->identity->user_guid;
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->created_at=time();
            if($model->save())
                return $this->redirect(['view-cate', 'id' => $model->cateid]);
        } else {
            $this->layout="@backend/views/layouts/auction_layout.php";
            return $this->render('create-cate', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionCreateRound()
    {
        $model = new AuctionRound();
    
        if ($model->load(Yii::$app->request->post()) ) {
            $model->user_guid=yii::$app->user->identity->user_guid;
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            $time=time();
            if($time>$model->start_time&&$time<$model->end_time){
                $model->status=1;
            }elseif($time>$model->end_time){
                $model->status=2;
            }
            $model->created_at=time();
            if($model->save())
                return $this->redirect(['view-round', 'id' => $model->id]);
        } else {
            return $this->render('create-round', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionUpdateRound($id)
    {
        $model = AuctionRound::findOne($id);
        $model->start_time=date('Y-m-d H:i',$model->start_time);
        $model->end_time=date('Y-m-d H:i',$model->end_time);
        if ($model->load(Yii::$app->request->post()) ) {
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            $time=time();
            if($time>$model->start_time&&$time<$model->end_time){
                $model->status=1;
            }elseif($time>$model->end_time){
                $model->status=2;
            }elseif ($time<$model->start_time){
                $model->status=0;
            }
            $model->created_at=time();
            if($model->save())
                return $this->redirect(['view-round', 'id' => $model->id]);
        } else {
          
            return $this->render('update-round', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new AuctionGoods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateGoods()
    {
        $model = new AuctionGoods();
        if(isset($_GET['cateid'])){
        $model->cateid=$_GET['cateid'];
        }
        if(isset($_GET['roundid'])){
            $model->roundid=$_GET['roundid'];
        }
      
        if ($model->load(Yii::$app->request->post())) {
            $model->user_guid=yii::$app->user->identity->user_guid;
            $model->goods_guid=CommonUtil::createUuid();
            $model->desc=@$_POST['goods-desc'];
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->current_price=$model->start_price;
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            $model->created_at=time();
            if($model->save())
                return $this->redirect(['view-goods', 'id' => $model->id]);     
        } else {
            $cate=AuctionCate::find()->all();
            $round=AuctionRound::find()->all();
            return $this->render('create-goods', [
                'model' => $model,
                'cate'=>$cate,
                'round'=>$round
            ]);
        }
    }

    /**
     * Updates an existing AuctionGoods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateGoods($id)
    {
        $model = $this->findModel($id);
        $cate=AuctionCate::find()->all();
        $model->start_time=date("Y-m-d H:i",$model->start_time);
        $model->end_time=date("Y-m-d H:i",$model->end_time);
        if ($model->load(Yii::$app->request->post())) {
             $model->desc=@$_POST['goods-desc'];
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $current_price=AuctionBidRec::find()->andWhere(['goods_guid'=>$model->goods_guid])->max('price');
            if(empty($current_price)){
                $model->current_price=$model->start_price;
            }else{
                $model->current_price=$current_price;
            }
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            $model->updated_at=time();
 
            if(!$model->save()){
                yii::$app->getSession()->addFlash('error','修改拍品失败!');
            }
                return $this->redirect(['view-goods', 'id' => $id]);    
        } else {
             $round=AuctionRound::find()->all();
            return $this->render('update-goods', [
                'model' => $model,
                'cate'=>$cate,
                'round'=>$round
            ]);
        }
    }
    
    /**
     * 重新发布拍品
     * @param unknown $id
     * @return \yii\web\Response|Ambigous <string, string>
     */
    public function actionRepostGoods($id)
    {
        $model=new AuctionGoods();
        $goods = $this->findModel($id);
        $model->goods_guid=CommonUtil::createUuid();
        $model->name=$goods->name;
        $model->desc=$goods->desc;
        $model->start_price=$goods->start_price;
        $model->delta_price=$goods->delta_price;
        $model->cateid=$goods->cateid;
        $model->roundid=$goods->roundid;
        $model->start_time=$goods->start_time;
        $model->end_time=$goods->end_time;
        $model->path=$goods->path;
        $model->photo=$goods->photo;
        $model->lowest_deal_price=$goods->lowest_deal_price;
        $model->fixed_price=$model->fixed_price;
        
        $cate=AuctionCate::find()->all();
        $model->start_time=date("Y-m-d H:i",$model->start_time);
        $model->end_time=date("Y-m-d H:i",$model->end_time);
        if ($model->load(Yii::$app->request->post())) {
            $model->desc=@$_POST['goods-desc'];
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->current_price=$model->start_price;
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            $model->created_at=time();
    
            if(!$model->save()){
                yii::$app->getSession()->addFlash('error','修改拍品失败!');
            }
            return $this->redirect(['view-goods', 'id' => $id]);
        } else {
              $round=AuctionRound::find()->all();
            return $this->render('repost-goods', [
                'model' => $model,
                'cate'=>$cate,
                'round'=>$round
            ]);
        }
    }
    
    public function actionUpdateCate($id)
    {
        $model = AuctionCate::findOne($id);
    
        if ($model->load(Yii::$app->request->post()) ) {
            $model->user_guid=yii::$app->user->identity->user_guid;
    
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->created_at=time();
            if($model->save())
                return $this->redirect(['view-cate', 'id' => $model->cateid]);
        } else {
            $this->layout="@backend/views/layouts/auction_layout.php";
            return $this->render('update-cate', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionViewCate($id)
    {
        $model=AuctionCate::findOne($id);
        $searchModel = new SearchAuctionGoods();
        $searchModel->cateid=$model->cateid;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view-cate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model
        ]);
    }
    
    public function actionViewRound($id){
        $model=AuctionRound::findOne($id);
        $searchModel = new SearchAuctionGoods();
        $searchModel->roundid=$model->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view-round', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model
        ]);
    }

    public function actionAuctionDeal($id){
        $model=AuctionGoods::findOne($id);
        $auctionRec=AuctionBidRec::findOne(['goods_guid'=>$model['goods_guid'],'is_leading'=>1]);
         $trans=yii::$app->db->beginTransaction();
         try{
        if(empty($auctionRec)){
            AuctionGoods::updateAll(['status'=>99],['goods_guid'=>$model['goods_guid']]);
        }else{
            AuctionGoods::updateAll(['status'=>2,'deal_user'=>$auctionRec->user_guid,'deal_price'=>$model['current_price']],['goods_guid'=>$model['goods_guid']]);
            $auctionRec->is_deal=1;
            if(!$auctionRec->save()) throw new \yii\db\Exception('更新拍卖纪录失败!');
            $message=new Message();
            $url=yii::$app->urlManager->createAbsoluteUrl(['auction/view','id'=>$model['id']]);
            $content="您好!您参与的拍卖-<span class='red'>".$model['name']."</span>-已结束,恭喜您已成交。请点击下面的按钮进行购买
            <a class='btn btn-success' href='$url' >立即购买</a>";
            $message->send(null, $auctionRec->user_guid, $content, Message::SYS);
            $trans->commit();
            yii::$app->getSession()->setFlash('success','标记成交成功!');
        }
         }catch (\yii\db\Exception $e){
             $trans->rollBack();
             yii::$app->getSession()->setFlash('error','标记成交失败!');
         }
         
         return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionAuctionFail($id){
       AuctionGoods::updateAll(['status'=>99],['id'=>$id]);
       yii::$app->getSession()->setFlash('success','标记流拍成功!');
       return $this->redirect(yii::$app->request->referrer);
    }
    /**
     * Deletes an existing AuctionGoods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteGoods($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    

    /**
     * Finds the AuctionGoods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AuctionGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuctionGoods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
