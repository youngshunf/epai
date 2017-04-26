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

/**
 * AuctionController implements the CRUD actions for AuctionGoods model.
 */
class SpAuctionController extends Controller
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
        $this->layout="@backend/views/layouts/sp_auction_layout.php";
        return parent::beforeAction($action);
    }

    /**
     * Lists all AuctionGoods models.
     * @return mixed
     */
    public function actionIndex()
    {
        /*  $dataProvider = new ActiveDataProvider([
            'query'=>AuctionCate::find(),
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);
         
       
        return $this->render('index', [        
            'dataProvider' => $dataProvider,
        ]); */
        return $this->redirect('round');
    }
    
    public function actionRound()
    {
        $dataProvider = new ActiveDataProvider([
            'query'=>AuctionRound::find()->andWhere(['post_type'=>2])->andWhere("auth_status != -1")->orderBy('sort desc,created_at desc'),
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);
    
        return $this->render('round', [
                'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * 专场审核通过
     * @param unknown $id
     */
    public function actionPassRound($id){
        $model=AuctionRound::findOne($id);
        $model->auth_status=1;
        $model->auth_user=yii::$app->user->identity->user_guid;
        if($model->save()){
            yii::$app->getSession()->setFlash('success','操作成功，专场-'.$model->name.'-审核通过!');
        }else{
            yii::$app->getSession()->setFlash('success','操作失败，专场-'.$model->name.'-未审核!');
        }
        return $this->redirect(yii::$app->request->referrer);
       
    }
    
    /**
     *批量审核通过
     * @param unknown $id
     */
    public function actionPassRoundGoods($id){
        $user_guid=yii::$app->user->identity->user_guid;
        AuctionGoods::updateAll(['auth_status'=>1,'auth_user'=>$user_guid],['roundid'=>$id]);
            yii::$app->getSession()->setFlash('success','操作成功，专场所有拍品审核通过!');
       
        return $this->redirect(yii::$app->request->referrer);
         
    }
    
    /**
     *批量审核不通过
     * @param unknown $id
     */
    public function actionDenyRoundGoods($id){
        $user_guid=yii::$app->user->identity->user_guid;
        AuctionGoods::updateAll(['auth_status'=>2,'auth_user'=>$user_guid],['roundid'=>$id]);
        yii::$app->getSession()->setFlash('success','操作成功，专场所有拍品审核不通过!');
         
        return $this->redirect(yii::$app->request->referrer);
         
    }
    
    /**
     * 专场审核不通过
     * @param unknown $id
     */
    public function actionDenyRound($id){
        $model=AuctionRound::findOne($id);
        $model->auth_status=2;
        $model->auth_user=yii::$app->user->identity->user_guid;
        if($model->save()){
            AuctionGoods::updateAll(['auth_status'=>2],['roundid'=>$id]);
            yii::$app->getSession()->setFlash('success','操作成功，专场-'.$model->name.'-审核未通过!');
        }else{
            yii::$app->getSession()->setFlash('success','操作失败，专场-'.$model->name.'-未审核!');
        }
        return $this->redirect(yii::$app->request->referrer);
         
    }
    
    /**
     * 拍品审核通过
     * @param unknown $id
     */
    public function actionPassGoods($id){
        $model=AuctionGoods::findOne($id);
        $model->auth_status=1;
        $model->auth_user=yii::$app->user->identity->user_guid;
        if($model->save()){
            yii::$app->getSession()->setFlash('success','操作成功，拍品-'.$model->name.'-审核通过!');
        }else{
            yii::$app->getSession()->setFlash('success','操作失败，拍品-'.$model->name.'-未审核!');
        }
        return $this->redirect(yii::$app->request->referrer);
         
    }
    
    /**
     * 拍品审核不通过
     * @param unknown $id
     */
    public function actionDenyGoods($id){
        $model=AuctionGoods::findOne($id);
        $model->auth_status=2;
        $model->auth_user=yii::$app->user->identity->user_guid;
        if($model->save()){
            yii::$app->getSession()->setFlash('success','操作成功，拍品-'.$model->name.'-审核不通过!');
        }else{
            yii::$app->getSession()->setFlash('success','操作失败，拍品-'.$model->name.'-未审核!');
        }
        return $this->redirect(yii::$app->request->referrer);
         
    }
    
    public function actionGoods()
    {
        $searchModel = new SearchAuctionGoods();
        $searchModel->post_type=2;
        $searchModel->queryType=1;
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
       $searchModel->auth_status=1;
       $searchModel->post_type=2;
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
        $model->start_time=date('Y-m-d H:1',$model->start_time);
        $model->end_time=date('Y-m-d H:1',$model->end_time);
        if ($model->load(Yii::$app->request->post()) ) {
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
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
