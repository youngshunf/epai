<?php

namespace frontend\Controllers;

use Yii;
use common\models\Wish;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
use common\models\CommonUtil;
use common\models\Album;
use common\models\ImageUploader;
use yii\filters\AccessControl;
use common\models\Order;
use yii\data\ActiveDataProvider;
use common\models\AuctionBidRec;
use common\models\LotteryRec;
use common\models\LotteryGoods;
use common\models\IdPhoto;
use common\models\AuctionRound;
use common\models\SearchAuctionGoods;
use common\models\AuctionCate;
use common\models\AuctionGoods;
use common\models\SearchOrder;
use common\models\MerchantGuarantee;
use yii\db\Exception;
use common\models\Message;

/**
 * WishController implements the CRUD actions for Wish model.
 */
class MerchantController extends Controller
{
    public $enableCsrfValidation = false;
    public $layout='merchant_layout';
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
              /*   'actions' => [
                    'delete' => ['post'],
                ], */
            ],
        ];
    }
    
    
    /**
     * Lists all Wish models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user=yii::$app->user->identity;
        if($user->merchant_role==0&&$user->merchant_apply_status==0){
            return $this->redirect(['merchant/merchant-register']);
        }
        if($user->merchant_role==0&&$user->merchant_apply_status==1){
            return $this->redirect(['merchant/id-authing']);
        }
        $user_guid=$user->user_guid;
        $model=User::findOne(['user_guid'=>$user_guid]);
       
      return $this->render('index',[
          'model'=>$model,        
      ]);
    }
    
    /**
     * 申请成为卖家
     * @return Ambigous <string, string>
     */
    public function actionMerchantRegister(){
        $this->layout='merchant_register';
        $user=yii::$app->user->identity;
        $model=User::findOne(['user_guid'=>$user->user_guid]);
        $model->setScenario('merchant-register');
        if($model->load(yii::$app->request->post())){
            //正面照片
            $idPhoto1=new IdPhoto();
            $photo1=ImageUploader::uploadByName('photo1');
            $idPhoto1->user_guid=$model->user_guid;
            if($photo1){
                $idPhoto1->path=$photo1['path'];
                $idPhoto1->photo=$photo1['photo'];
            }
            $idPhoto1->type=1;
            $idPhoto1->created_at=time();
            $idPhoto1->save();
            
            //反面照片
            $idPhoto2=new IdPhoto();
            $photo2=ImageUploader::uploadByName('photo2');
            $idPhoto2->user_guid=$model->user_guid;
            if($photo2){
                $idPhoto2->path=$photo2['path'];
                $idPhoto2->photo=$photo2['photo'];
            }
            $idPhoto2->type=2;
            $idPhoto2->created_at=time();
            $idPhoto2->save();
            
            //手持身份证照片
            $idPhoto3=new IdPhoto();
            $photo3=ImageUploader::uploadByName('photo3');
            $idPhoto3->user_guid=$model->user_guid;
            if($photo3){
                $idPhoto3->path=$photo3['path'];
                $idPhoto3->photo=$photo3['photo'];
            }
            $idPhoto3->type=3;
            $idPhoto3->created_at=time();
            $idPhoto3->save();
            
            $model->merchant_apply_status=1;
            if($model->save()){
                return $this->redirect('id-authing');
            }
            
        }
        return $this->render('merchant-register',[
            'model'=>$model
        ]);
    }
    
    //实名认证审核中
    public function actionIdAuthing(){
        $this->layout='merchant_register';
        return $this->render('id-authing');
    }

    /**
     *专场管理
     * @param integer $id
     * @param string $wish_guid
     * @return mixed
     */
    public function actionRound()
    {
        $user_guid=yii::$app->user->identity->user_guid;
        $dataProvider = new ActiveDataProvider([
            'query'=>AuctionRound::find()->andWhere(['user_guid'=>$user_guid])->orderBy('sort desc,created_at desc'),
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);
        
        $countRound=AuctionRound::find()->andWhere(['user_guid'=>$user_guid])->count();
        return $this->render('round', [
            'dataProvider' => $dataProvider,
            'countRound'=>$countRound
        ]);
    }
    
    /**
     * 发布专场
     * @return \yii\web\Response|Ambigous <string, string>
     */
    public function actionCreateRound()
    {
        $user=yii::$app->user->identity;
        $user_guid=$user->user_guid;
        $countRound=AuctionRound::find()->andWhere(['user_guid'=>$user_guid])->count();
        if($user->merchant_role==1&&$countRound>=yii::$app->params['normalMerchant.round']){
            yii::$app->getSession()->setFlash('error','您是普通卖家,只能发布一个专场,如果需要发布多个专场,请升级为高级卖家');
            return $this->redirect(yii::$app->request->referrer);
        }elseif ($user->merchant_role==2&&$countRound>=yii::$app->params['seniorMerchant.round']){
            yii::$app->getSession()->setFlash('error','您是高级卖家,高级卖家最多只能发布两个专场!');
            return $this->redirect(yii::$app->request->referrer);
        }
        
        $model = new AuctionRound();
    
        if ($model->load(Yii::$app->request->post()) ) {
            $model->user_guid=$user_guid;
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->post_type=2;
            $model->auth_status=-1;
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
    
    /**
     * 修改专场
     * @param unknown $id
     * @return \yii\web\Response|Ambigous <string, string>
     */
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
     * 查看专场
     * @param unknown $id
     * @return Ambigous <string, string>
     */
    public function actionViewRound($id){
        $model=AuctionRound::findOne($id);
        $searchModel = new SearchAuctionGoods();
        $searchModel->roundid=$model->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $user_guid=yii::$app->user->identity->user_guid;
        $countGoods=AuctionGoods::find()->andWhere(['user_guid'=>$user_guid,'roundid'=>$model->id])->count();
        return $this->render('view-round', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model,
            'countGoods'=>$countGoods
        ]);
    }
    
    /**
     * 提交审核
     * @param unknown $id
     */
    public function actionSubmitAuth($id){
        AuctionRound::updateAll(['auth_status'=>0],['id'=>$id]);
        AuctionGoods::updateAll(['auth_status'=>0],['roundid'=>$id]);
        yii::$app->getSession()->setFlash('success','提交审核成功,请等待管理员审核!');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionGoods()
    {
        $user_guid=yii::$app->user->identity->user_guid;
        $searchModel = new SearchAuctionGoods();
        $cate="";
        if(isset($_GET['cateid'])){
            $cateid=$_GET['cateid'];
            $searchModel->cateid=$cateid;
            $cate=AuctionCate::findOne($cateid);
        }
        $searchModel->user_guid=$user_guid;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('goods', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'cate'=>$cate
        ]);
    }
    
    /**
     * 发布拍品
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
        $user=yii::$app->user->identity;
        
        $countRoundGoods=AuctionGoods::find()->andWhere(['user_guid'=>$user->user_guid,'roundid'=>$model->roundid])->count();
        if($user->merchant_role==1&&$countRoundGoods>=yii::$app->params['normalMerchant.goods']){
            yii::$app->getSession()->setFlash('error','您是普通卖家,每个专场最多只能发布'.yii::$app->params['normalMerchant.goods'].'件拍品');
            return $this->redirect(yii::$app->request->referrer);
        }elseif ($user->merchant_role==2&&$countRoundGoods>=yii::$app->params['seniorMerchant.goods']){
            yii::$app->getSession()->setFlash('error','您是高级卖家,每个专场最多只能发布'.yii::$app->params['seniorMerchant.goods'].'个拍品!');
            return $this->redirect(yii::$app->request->referrer);
        }
        
        if ($model->load(Yii::$app->request->post())) {
            
            $model->user_guid=$user->user_guid;
            $model->goods_guid=CommonUtil::createUuid();
            $model->desc=@$_POST['goods-desc'];
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->post_type=2;
            $model->auth_status=-1;
            $model->current_price=$model->start_price;
            if($user->merchant_role==1){
                $model->lowest_deal_price=$model->start_price;
            }
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            $model->created_at=time();
            if($model->save())
                return $this->redirect(['view-goods', 'id' => $model->id]);
        } else {
            $user_guid=yii::$app->user->identity->user_guid;
            $cate=AuctionCate::find()->all();
            $round=AuctionRound::find()->andWhere(['user_guid'=>$user_guid])->all();
            return $this->render('create-goods', [
                'model' => $model,
                'cate'=>$cate,
                'round'=>$round
            ]);
        }
    }
    
    public  function actionDeleteGoods($id){
        AuctionGoods::deleteAll(['id'=>$id]);
        yii::$app->getSession()->setFlash('success','拍品删除成功!');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    
    /**
     * 订单管理
     * @return mixed
     */
    public function actionOrder()
    {
        $searchModel = new SearchOrder();
        $searchModel->merchant_user=yii::$app->user->identity->user_guid;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        return $this->render('order', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 查看订单
     * @param unknown $id
     * @return Ambigous <string, string>
     */
       public function actionViewOrder($id)
    {
        $model=Order::findOne($id);
        return $this->render('view-order', [
            'model' => $model,
        ]);
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
     *查看拍品
     * @param integer $id
     * @return mixed
     */
    public function actionViewGoods($id)
    {
        return $this->render('view-goods', [
            'model' => $this->findModel($id),
        ]);
    }
        
    public  function actionProfile($user_guid){
        $this->layout="@frontend/views/layouts/user_layout.php";
        $model=User::findOne(['user_guid'=>$user_guid]);
        return $this->render('profile',['model'=>$model]);
    }
    
    public function actionAddUserProfile(){
        $user_guid= yii::$app->user->identity->user_guid;
       $model=User::findOne(['user_guid'=>$user_guid]);
        $model->setScenario('update');
        if ($model->load(Yii::$app->request->post()) ) {
            $model->is_profile_done=1;
            $model->age=date('Y')-date('Y',strtotime($model->birthday));
            $model->created_at=time();
            if($model->save())
               return $this->redirect(['create']);
        } else {
            return $this->render('add-user-profile', [
                'model' => $model,
            ]);
        }
        
    }
        
        
      /**
       * 卖家须知
       * @param unknown $id
       * @return Ambigous <string, string>
       */
    public function actionMerchantNote(){
        return $this->render('merchant-note');
    }
    
    /**
     * 升级为高级卖家
     * @param unknown $id
     * @return Ambigous <string, string>
     */
    public function actionMerchantUpgrade(){
        return $this->render('merchant-upgrade');
    }
    
    /**
     * 升级为高级卖家
     * @param unknown $id
     * @return Ambigous <string, string>
     */
    public function actionMerchantUpgradeDo(){
        $user=yii::$app->user->identity;
     //开始事务
        $trans=yii::$app->db->beginTransaction();      
        try{ 
        $merchantGuarantee=new MerchantGuarantee();
        $merchantGuarantee->user_guid=$user->user_guid;        
        $merchantGuarantee->fee_guid=CommonUtil::createUuid();
        $merchantGuarantee->amount=CommonUtil::MERCHANT_FEE;
        $merchantGuarantee->merchant_role=2;
        
        $merchantGuarantee->created_at=time();
        if(!$merchantGuarantee->save()) throw new Exception("insert guarantee_fee error");
        
        $order=new Order();
        $order->user_guid=$user->user_guid;
        $order->order_guid=CommonUtil::createUuid();
        $order->orderno=Order::getOrderNO(Order::TYPE_MERCHANT_GUARANTEE);
        $order->type=Order::TYPE_MERCHANT_GUARANTEE;
        $order->biz_guid=$merchantGuarantee->fee_guid;
        $order->amount=$merchantGuarantee->amount;
        $order->number=1;
        $order->goods_name="高级卖家保证金";
        $order->created_at=time();
        if(!$order->save()) throw new Exception("insert Order error");
        
        $trans->commit();
        }catch (Exception $e){
            $trans->rollBack();
            yii::$app->getSession()->setFlash('error',"提交保证金失败,请稍候重试!");
            return $this->redirect(yii::$app->getRequest()->getReferrer());
        }
        
        return $this->redirect(['site/pay-order','order_guid'=>$order->order_guid]);
    }
    
    public function actionMerchantMessage(){
        $user_guid=yii::$app->user->identity->user_guid;
        Message::updateAll(['is_read'=>1],['to_user'=>$user_guid,'type'=>Message::MERCHANT]);
        $dataProvider=new ActiveDataProvider([
            'query'=>Message::find()->andWhere(['to_user'=>$user_guid,'type'=>Message::MERCHANT])->orderBy('created_at desc'),
        ]);
    
        return $this->render('merchant-message',[
            'dataProvider'=>$dataProvider
        ]);
    }
    
    
    protected function findModel($id)
    {
        if (($model = AuctionGoods::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
