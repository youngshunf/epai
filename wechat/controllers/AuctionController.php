<?php

namespace wechat\controllers;

use Yii;
use common\models\AuctionGoods;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use common\models\CommonUtil;
use yii\filters\AccessControl;
use common\models\AuctionBidRec;
use yii\db\Exception;
use common\models\AuctionAgentBid;
use common\models\GuaranteeFee;
use common\models\Order;
use common\models\User;
use common\models\AuctionCate;
use common\models\AuctionRound;
use common\models\Address;
use common\models\Siteinfo;
use common\models\WeChatTemplate;
use common\models\GoodsLove;
use yii\helpers\Json;

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
                    'actions' => ['submit-guarantee'],
                    'allow' => true,
                    'roles' => ['?'],
                    ],
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
                    'delete' => ['post','submit-guarantee'],
                ],
            ],
        ];
    }

    public function beforeAction($action){
        if($action->id=="index" ||$action->id=='view'){
               yii::$app->getUser()->setReturnUrl(yii::$app->getRequest()->getAbsoluteUrl());
        }
        
     if(!yii::$app->user->isGuest && $action->id=='submit-bid'){
            if(!empty(yii::$app->user->identity->openid)&&yii::$app->user->identity->is_band==0){
                yii::$app->getSession()->setFlash('error','您需要先绑定手机号才能出价!');
                return $this->redirect(['site/band-user']);
            }
        }
        
        CommonUtil::checkAllAuction();
        return parent::beforeAction($action);
    }
    /**
     * Lists all AuctionGoods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $now=time();
        $status=0;
        if(isset($_GET['status'])){
            $status=$_GET['status'];
        }
        
        $where="";
        if($status==0){
            $where=" status=1";
        }elseif ($status==1){
            $where="  status=0 ";
        }elseif ($status==2){
            $where=" end_time < $now ";
        }
        
        $cateid=0;
        if(isset($_GET['cateid'])){
            $cateid=$_GET['cateid'];
             if($cateid!=0){
              $where .=" and cateid=$cateid ";
            }
        }
   
      $dataProvider = new ActiveDataProvider([
                'query'=>AuctionGoods::find()->andWhere($where)->andWhere(['auth_status'=>1,'post_type'=>1])->orderBy('sort desc,created_at asc'),
                'pagination'=>[
                    'pagesize'=>18
                ]
                ]);

      if($status==1){
          return $this->render('preview', [
              'dataProvider' => $dataProvider,
              'status'=>$status,
              'cateid'=>$cateid
          ]);
      }else{            
        return $this->render('index', [        
            'dataProvider' => $dataProvider,
           'status'=>$status,
            'cateid'=>$cateid
        ]);
      }
    }
    
    public function actionSearchDo()
    {
        $now=time();
        $status=0;
        if(isset($_POST['status'])){
            $status=$_POST['status'];
        }
    
        $where="";
        if($status==0){
            $where=" start_time <= $now and end_time >=$now and auth_status=1";
        }elseif ($status==1){
            $where="  start_time > $now and auth_status=1";
        }elseif ($status==2){
            $where=" end_time < $now  auth_status=1";
        }
    
        $cateid=0;
        if(isset($_POST['cateid'])){
            $cateid=$_POST['cateid'];
            if($cateid!=0){
              $where .=" and cateid=$cateid ";
            }
        }
        
        $keywords="";
        if(isset($_POST['keywords'])){
            $keywords=$_POST['keywords'];
            $where.=" and name like '%$keywords%' ";
        }
         
        $dataProvider = new ActiveDataProvider([
            'query'=>AuctionGoods::find()->andWhere($where)->orderBy('sort desc,created_at asc'),
            'pagination'=>[
                'pagesize'=>18
            ]
        ]);
    
        if($status==1){
        return $this->render('preview', [
            'dataProvider' => $dataProvider,
            'status'=>$status,
            'cateid'=>$cateid,
            'keywords'=>$keywords
            ]);
        }else{
        return $this->render('index', [
        'dataProvider' => $dataProvider,
            'status'=>$status,
             'cateid'=>$cateid,
            'keywords'=>$keywords
        ]);
        }
        }
    
    public function actionCate(){
        $dataProvider=new ActiveDataProvider([
            'query'=>AuctionCate::find()->orderBy('created_at asc'),
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);
        
        return $this->render('cate',[
            'dataProvider'=>$dataProvider
        ]);
    }
    
    
    
    public function actionPreview()
    {
        $now=time();
        $cateid="";
        if(isset($_GET['cateid'])){
            $cateid=$_GET['cateid'];
            $dataProvider = new ActiveDataProvider([
                'query'=>AuctionGoods::find()->andWhere(" cateid=$cateid and status=0 and auth_status=1 and post_type=1")->orderBy('sort desc,created_at asc'),
                'pagination'=>[
                    'pagesize'=>18
                ]
            ]);
        }else{
        $dataProvider = new ActiveDataProvider([
            'query'=>AuctionGoods::find()->andWhere("  status=0 and auth_status=1 and post_type=1 ")->orderBy('sort desc,created_at asc'),
                'pagination'=>[
                    'pagesize'=>18
                ]
            ]);
        }
                 
                return $this->render('preview', [
                    'dataProvider' => $dataProvider,
                    'cateid'=>$cateid
                        ]);
    }
    
    public function actionRound(){
        $now=time();
        $dataProvider = new ActiveDataProvider([
            'query'=>AuctionRound::find()->andWhere(" offline=0 && post_type = 1 ")->orderBy('sort desc,start_time asc'),
            'pagination'=>[
                'pagesize'=>20
            ]
        ]);
         
        return $this->render('round', [
            'dataProvider' => $dataProvider,
         ]);
    }
    
    //个人专场
    public function actionPersonalRound(){
        $time=time();
        $dataProvider = new ActiveDataProvider([
            'query'=>AuctionRound::find()->andWhere(['auth_status'=>1,'post_type'=>2])->orderBy('sort asc,created_at asc'),
            'pagination'=>[
                'pagesize'=>20
            ]
        ]);
         
        return $this->render('round', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionRoundView($id){
    $dataProvider = new ActiveDataProvider([
        'query'=>AuctionGoods::find()->andWhere(['roundid'=>$id])->orderBy('sort asc,start_time asc'),
            'pagination'=>[
                'pagesize'=>20
            ]
        ]);
     $round=AuctionRound::findOne($id);
     $picUrl=yii::getAlias('@photo').'/'.$round->path.'mobile/'.$round->photo;
            return $this->render('round-view', [
                'dataProvider' => $dataProvider,
                'round'=>$round,
                'picUrl'=>$picUrl
                ]);
    
    }
    
    public function actionGetRoundlist(){
        $data=$_GET['data'];
        $pageIndex=$data['pageIndex'];
        $pageSize=$data['pageSize'];
        if(empty($pageSize)){
            $pageSize=5;
        }
        $roundid=$data['roundid'];
        $roundList=AuctionGoods::find()->andWhere(['roundid'=>$roundid])->orderBy('sort asc,start_time asc')->limit($pageSize)->offset(($pageIndex-1)*$pageSize)->all();
        return Json::encode($roundList);
    }
    
    public function actionGoodsLove($goodsid){
        $user_guid=yii::$app->user->identity->user_guid;
        $goodsLove=new GoodsLove();
        $goodsLove->user_guid=$user_guid;
        $goodsLove->goodsid=$goodsid;
        $goodsLove->created_at=time();
        if($goodsLove->save()){
            yii::$app->getSession()->setFlash('success','收藏成功!');
            return $this->redirect(yii::$app->request->referrer);
        }
        yii::$app->getSession()->setFlash('error','收藏失败!');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionGoodsloveCancel($goodsid){
        $user_guid=yii::$app->user->identity->user_guid;
        $goodsLove=GoodsLove::findOne(['user_guid'=>$user_guid,'goodsid'=>$goodsid]);
        if($goodsLove->delete()){
            yii::$app->getSession()->setFlash('success','收藏已取消!');
            return $this->redirect(yii::$app->request->referrer);
        }
    }
    
  public function actionFixedBuy($goods_guid){
        $goods=AuctionGoods::findOne(['goods_guid'=>$goods_guid]);
        if(empty($goods->fixed_price)){
            yii::$app->getSession()->setFlash('error','该商品没有一口价,不能一口价购买!');
            return $this->redirect(yii::$app->request->referrer);
        }
        
        $user_guid=yii::$app->user->identity->user_guid;
        //获取用户默认收货地址
        $address=Address::findOne(['user_guid'=>$user_guid,'is_default'=>1]);
        if(empty($address)){
            yii::$app->getSession()->setFlash('error','对不起,你没有设置默认收货地址!');
            return $this->redirect(yii::$app->request->referrer);
        }
        
        $order=new Order();
        $order->user_guid=yii::$app->user->identity->user_guid;
        $order->order_guid=CommonUtil::createUuid();
        $order->orderno=Order::getOrderNO(Order::TYPE_AUCTION);
        $order->type=Order::TYPE_AUCTION;
        $order->goods_name=$goods->name;
        $order->amount=$goods->fixed_price;
        $order->address_id=$address->id;
       $order->address=$address['province'].' '.$address['city'].' '.$address['district'].' '.$address['address'].' '.$address['company'].' '.$address['name'].' '.$address['phone'];
        $order->number=1;
        $order->biz_guid=$goods->goods_guid;
        $order->created_at=time();
        if($order->save()){
            return $this->redirect(['site/pay-order','order_guid'=>$order->order_guid]);
        }
    }
    
    public function actionBuyAuction($id){
        $auctionGoods=AuctionGoods::findOne($id);
        $user_guid=yii::$app->user->identity->user_guid;
        //验证用户是否是成交用户
        if($auctionGoods->status!=2||$auctionGoods->deal_user!=$user_guid){
            yii::$app->getSession()->setFlash('error','对不起,您不是成交用户,不能进行购买!');
            return $this->redirect(yii::$app->request->referrer);
        }
        $order=Order::find()->andWhere(['user_guid'=>$user_guid,'biz_guid'=>$auctionGoods->goods_guid])->one();
        if(!empty($order)){
            return $this->redirect(['site/pay-order','order_guid'=>$order->order_guid]);
        }else{
            $order=new Order();
            $order->merchant_user=$auctionGoods->user_guid;
            $order->order_type=$auctionGoods->post_type;
            $order->user_guid=$user_guid;
            $order->order_guid=CommonUtil::createUuid();
            $order->orderno=Order::getOrderNO(Order::TYPE_AUCTION);
            $order->type=Order::TYPE_AUCTION;
            $order->goods_name=$auctionGoods->name;
            $order->total_amount=$auctionGoods->deal_price;
            $order->amount=$auctionGoods->deal_price;
            $address=Address::findOne(['user_guid'=>$user_guid,'is_default'=>1]);
            if(!empty($address)){
            $order->address_id=$address->id;
            $order->address=$address['province'].' '.$address['city'].' '.$address['district'].' '.$address['address'].' '.$address['company'].' '.$address['name'].' '.$address['phone'];
            }
            $order->number=1;
            $order->biz_guid=$auctionGoods->goods_guid;
            $order->created_at=time();
            if($order->save()){
                return $this->redirect(['site/pay-order','order_guid'=>$order->order_guid]);
            }
        }
        
         yii::$app->getSession()->setFlash('error','对不起,订单不存在!');
            return $this->redirect(yii::$app->request->referrer);
        
    }
    
    //增加收货地址
    public function actionNewAddress(){
        $user_guid=yii::$app->user->identity->user_guid;
        Address::updateAll(['is_default'=>0],['user_guid'=>$user_guid]);
        $address=new Address();
        $address->user_guid=$user_guid;
        $address->province=$_POST['province'];
        $address->city=$_POST['city'];
        $address->district=$_POST['district'];
        $address->address=$_POST['address'];
        $address->name=$_POST['name'];
        $address->phone=$_POST['mobile'];
        $address->company=@$_POST['company'];
        $address->is_default=1;
        $address->created_at=time();
        if($address->save()){
            yii::$app->getSession()->setFlash('success','收货地址增加成功!');
        }else{
            yii::$app->getSession()->setFlash('success','收货地址增加失败!');
        }
    
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionNewOrderAddress(){
        $user_guid=yii::$app->user->identity->user_guid;
        Address::updateAll(['is_default'=>0],['user_guid'=>$user_guid]);
        $orderid=$_POST['orderid'];
        $order=Order::findOne($orderid);
        if(empty($order)){
            yii::$app->getSession()->setFlash('success','订单为找到!');
            return $this->redirect(yii::$app->request->referrer);
        }
        $address=new Address();
        $address->user_guid=$user_guid;
        $address->province=$_POST['province'];
        $address->city=$_POST['city'];
        $address->district=$_POST['district'];
        $address->address=$_POST['address'];
        $address->name=$_POST['name'];
        $address->phone=$_POST['mobile'];
        $address->company=@$_POST['company'];
        $address->is_default=1;
        $address->created_at=time();
        if($address->save()){
            $order->address_id=$address->id;
            $order->address=$address['province'].' '.$address['city'].' '.$address['district'].' '.$address['address'].' '.$address['company'].' '.$address['name'].' '.$address['phone'];
            $order->save();
            $allOrder=Order::find()->andWhere(['user_guid'=>$user_guid,'address_id'=>'0'])->all();
            foreach ($allOrder as $v){
                if(empty($v->address)){
                    $v->address_id=$address->id;
                    $v->address=$address['province'].' '.$address['city'].' '.$address['district'].' '.$address['address'].' '.$address['company'].' '.$address['name'].' '.$address['phone'];
                    $v->save();
                }
            }
            yii::$app->getSession()->setFlash('success','收货地址增加成功!');
            return $this->redirect(['site/pay-order','order_guid'=>$order->order_guid]);
        }else{
            yii::$app->getSession()->setFlash('success','收货地址增加失败!');
        }
    
        return $this->redirect(yii::$app->request->referrer);
    }
    
    /**
     * Displays a single AuctionGoods model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
     
        $model=$this->findModel($id);
        $model->count_view+=1;
        $model->save();
        $user_guid=yii::$app->user->identity->user_guid;
        CommonUtil::checkAllAuction();
        //判断用户是否交保证金
        $guarantee=0;
//         if(!yii::$app->user->isGuest){
//            if( yii::$app->user->identity->role_id==3){
//                $guarantee=yii::$app->user->identity->guarantee;
//            }elseif( yii::$app->user->identity->role_id==2){
//                $user_guid=yii::$app->user->identity->user_guid;
//                $guaranteeFee=GuaranteeFee::findOne(['user_guid'=>$user_guid,'goods_guid'=>$model->goods_guid,'is_pay'=>1]);
//                if(!empty($guaranteeFee)){
//                    $guarantee=1;
//                }
//            }
//         }
        
    $bidRecData=new ActiveDataProvider([
            'query'=>AuctionBidRec::find()->andWhere(['goods_guid'=>$model->goods_guid])->orderBy("price desc,created_at desc"),
            'pagination'=>[
                'pagesize'=>5
            ]
        ]);
    $delta_price=20;
    if($model->current_price>=200&&$model->current_price<500){
        $delta_price=30;
    }elseif($model->current_price>=500&&$model->current_price<1000){
        $delta_price=50;
    }elseif($model->current_price>=1000&&$model->current_price<2000){
        $delta_price=100;
    }elseif($model->current_price>=2000&&$model->current_price<5000){
        $delta_price=300;
    }elseif($model->current_price>=5000&&$model->current_price<10000){
        $delta_price=500;
    }elseif($model->current_price>=10000){
        $delta_price=1000;
    }
    
    $auctionRule=Siteinfo::findOne(['id'=>4]);
    $auctionTimes=AuctionBidRec::find()->andWhere(['goods_guid'=>$model->goods_guid])->count();
    $hasLove=false;
    $goodsLove=GoodsLove::findOne(['user_guid'=>$user_guid,'goodsid'=>$id]);
    if(!empty($goodsLove)){
        $hasLove=true;
    }
    $picUrl=yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo;
    $description=CommonUtil::cutHtml($model->desc);
        return $this->render('view', [
            'model' =>$model ,
            'cateid'=>$model->cateid,
            'guarantee'=>$guarantee,
            'bidRecData'=>$bidRecData,
            'auctionTimes'=>$auctionTimes,
            'auctionRule'=>$auctionRule,
            'delta_price'=>$delta_price,
            'hasLove'=>$hasLove,
            'picUrl'=>$picUrl,
            'description'=>$description
        ]);
    }
    
    public function actionViewFragment($id)
    {
        
        $model=$this->findModel($id);
        $model->count_view+=1;
        $model->save();
        $user_guid=yii::$app->user->identity->user_guid;
        CommonUtil::checkAllAuction();
        //判断用户是否交保证金
        $guarantee=0;
        $bidRecData=new ActiveDataProvider([
            'query'=>AuctionBidRec::find()->andWhere(['goods_guid'=>$model->goods_guid])->orderBy("price desc,created_at desc"),
            'pagination'=>[
                'pagesize'=>5
            ]
        ]);
        $delta_price=20;
        if($model->current_price>=200&&$model->current_price<500){
            $delta_price=30;
        }elseif($model->current_price>=500&&$model->current_price<1000){
            $delta_price=50;
        }elseif($model->current_price>=1000&&$model->current_price<2000){
            $delta_price=100;
        }elseif($model->current_price>=2000&&$model->current_price<5000){
            $delta_price=300;
        }elseif($model->current_price>=5000&&$model->current_price<10000){
            $delta_price=500;
        }elseif($model->current_price>=10000){
            $delta_price=1000;
        }
        
        $auctionRule=Siteinfo::findOne(['id'=>4]);
        $auctionTimes=AuctionBidRec::find()->andWhere(['goods_guid'=>$model->goods_guid])->count();
        $hasLove=false;
        $goodsLove=GoodsLove::findOne(['user_guid'=>$user_guid,'goodsid'=>$id]);
        if(!empty($goodsLove)){
            $hasLove=true;
        }
        $picUrl=yii::getAlias('@photo').'/'.$model->path.'mobile/'.$model->photo;
        $description=CommonUtil::cutHtml($model->desc);
        return $this->renderAjax('view-fragment', [
            'model' =>$model ,
            'cateid'=>$model->cateid,
            'guarantee'=>$guarantee,
            'bidRecData'=>$bidRecData,
            'auctionTimes'=>$auctionTimes,
            'auctionRule'=>$auctionRule,
            'delta_price'=>$delta_price,
            'hasLove'=>$hasLove,
            'picUrl'=>$picUrl,
            'description'=>$description
        ]);
     }
     
    public function actionSubmitGuarantee(){
        yii::$app->getUser()->setReturnUrl(yii::$app->getRequest()->referrer);
        $goods_guid=$_GET['goods-guid'];
        $user_guid=yii::$app->user->identity->user_guid;        
        //开始事务
        $trans=yii::$app->db->beginTransaction();      
        try{ 
        $guaranteeFee=new GuaranteeFee();
        $guaranteeFee->user_guid=$user_guid;        
        $guaranteeFee->fee_guid=CommonUtil::createUuid();
        $guaranteeFee->guarantee_fee=CommonUtil::GUARANTEE_FEE;
        $guaranteeFee->goods_guid=$goods_guid;
        $guaranteeFee->user_role=$_GET['role'];
        
        $guaranteeFee->created_at=time();
        if(!$guaranteeFee->save()) throw new Exception("insert guarantee_fee error");
        
        $order=new Order();
        $order->user_guid=$user_guid;
        $order->order_guid=CommonUtil::createUuid();
        $order->orderno=Order::getOrderNO(Order::TYPE_GUARANTEE);
        $order->type=Order::TYPE_GUARANTEE;
        $order->biz_guid=$guaranteeFee->fee_guid;
        $order->amount=$guaranteeFee->guarantee_fee;
        $order->number=1;
        $order->goods_name=CommonUtil::getDescByValue('user', 'role_id', $guaranteeFee->user_role)."-拍卖保证金";
        $order->created_at=time();
        if(!$order->save()) throw new Exception("insert Order error");
        
        $trans->commit();
        }catch (Exception $e){
            $trans->rollBack();
            yii::$app->getSession()->setFlash('error',"提交保证金失败,请稍候重试!");
            return $this->redirect(yii::$app->getRequest()->getReferrer());
        }

        return $this->redirect(["site/pay-order",
            'order_guid'=>$order->order_guid,
        ]);
    }
    
    public function actionSubmitBid(){
        $user_guid=yii::$app->user->identity->user_guid;
        if(!empty(yii::$app->user->identity->openid)&&yii::$app->user->identity->is_band==0){
            yii::$app->getSession()->setFlash('error','您需要先绑定手机号才能出价!');
            return $this->redirect(['site/band-user']);
        }
        $goods_guid=$_POST['goods-guid'];
        $price=$_POST['bid-price'];
        $auctionGoods=AuctionGoods::findOne(['goods_guid'=>$goods_guid]);
        $lastLeadingUser=$auctionGoods->leading_user;
        $auctionTimes=AuctionBidRec::find()->andWhere(['goods_guid'=>$goods_guid])->count();
        $now=time();
        if($auctionGoods->leading_user==$user_guid && ($auctionGoods->current_price>$auctionGoods->reverse_price)){
            yii::$app->getSession()->setFlash('error',"您已经是最高价了,无需再出价");
            return $this->redirect(['view','id'=>$auctionGoods->id]);
        }
        if($now>$auctionGoods->end_time){
            yii::$app->getSession()->setFlash('error',"出价失败,拍卖已结束,下次早点来哦.");
            return $this->redirect(['view','id'=>$auctionGoods->id]);
        }
        if($auctionTimes==0){
            if($price<$auctionGoods->current_price){
                yii::$app->getSession()->setFlash('error',"出价失败,竞拍价格必须大于当前价格.");
                return $this->redirect(['view','id'=>$auctionGoods->id]);
            }
        }else{
            if($price<=$auctionGoods->current_price){
                yii::$app->getSession()->setFlash('error',"出价失败,竞拍价格必须大于当前价格.");
                return $this->redirect(['view','id'=>$auctionGoods->id]);
            }
        }
        $delta_price=20;
        if($auctionGoods->current_price>=200&&$auctionGoods->current_price<500){
            $delta_price=30;
        }elseif ($auctionGoods->current_price>=500&&$auctionGoods->current_price<1000){
            $delta_price=50;
        }elseif ($auctionGoods->current_price>=1000&&$auctionGoods->current_price<2000){
            $delta_price=100;
        }elseif ($auctionGoods->current_price>=2000&&$auctionGoods->current_price<5000){
            $delta_price=300;
        }elseif ($auctionGoods->current_price>=5000&&$auctionGoods->current_price<10000){
            $delta_price=500;
        }elseif($auctionGoods->current_price>=10000){
            $delta_price=1000;
        }
        if(($price-$auctionGoods->current_price) < $delta_price){
            yii::$app->getSession()->setFlash('error',"出价失败,竞拍价格必须大于加价幅度.");
            return $this->redirect(['view','id'=>$auctionGoods->id]);
        }
        
        //开始事务
        $transaction=yii::$app->db->beginTransaction();
        try{       
           $maxPrice=AuctionBidRec::find()->andWhere(['goods_guid'=>$goods_guid])->max('price');
           if($price<$maxPrice){
               throw new Exception("您的出价不是最高的"); 
               yii::$app->getSession()->setFlash('success',"您的出价已被超越,出价无效,请重新出价!");
               return $this->redirect(yii::$app->request->referrer);
           }
           AuctionBidRec::updateAll(['is_leading'=>0],['goods_guid'=>$goods_guid]);
            //增加出价记录
        $bidRec=new AuctionBidRec();
        $bidRec->goods_guid=$goods_guid;
        $bidRec->user_guid=$user_guid;
        $bidRec->price=$price;
        $bidRec->is_leading=1;
        $bidRec->created_at=time();
        if(!$bidRec->save()) throw new Exception(" insert db auction_bid_rec error"); 
        
        //更新拍品表
        $auctionGoods->count_auction+=1;       
        $auctionGoods->current_price=$price;
        $leading_user=$auctionGoods->leading_user;
        $auctionGoods->leading_user=$user_guid;
        if($auctionGoods->end_time - time() <=60){
            $auctionGoods->end_time +=90;
            $round=AuctionRound::findOne($auctionGoods->roundid);
            if(!empty($round)){
                if($round->end_time<$auctionGoods->end_time){
                    $round->end_time=$auctionGoods->end_time;
                    $round->status=1;
                    $round->save();
                }
            }
        }
        $auctionGoods->updated_at=time();
        if(!$auctionGoods->save()) throw new Exception(" insert db auction_goods error"); 
        
        $transaction->commit();
        if($user_guid != $leading_user){
            $this->SendTemplateMessage($leading_user, $auctionGoods->id);
        }
        }catch (Exception $e){
            $transaction->rollBack();
            yii::$app->getSession()->setFlash('error',"出价失败,请稍候重试!");
            return $this->redirect(['view','id'=>$auctionGoods->id]);
        }
        
        if($auctionGoods->current_price<$auctionGoods->reverse_price && $auctionGoods->reverse_price !=0.00){
            $msg="您的出价未达到保留价，您可以继续出价!";
            if($auctionGoods->eval_price!='0.00'){
                $msg .="<br>小火估价 ￥".$auctionGoods->eval_price;
            }
            yii::$app->getSession()->setFlash('success',$msg);
        }elseif($auctionGoods->current_price>$auctionGoods->reverse_price && $auctionGoods->reverse_price !=0.00){
            yii::$app->getSession()->setFlash('success',"出价成功!");
        }else{
            yii::$app->getSession()->setFlash('success',"出价成功!");
        }
//         //代理出价
//         if($this->AgentBid($goods_guid)){
//             yii::$app->getSession()->setFlash('success','您的出价已被超越!');
//         }
        
//         //系统自动加价
//         if($this->AutoBid($goods_guid)){
//             yii::$app->getSession()->setFlash('success','您的出价已被超越!');
//         }
           
        return $this->redirect(yii::$app->request->referrer);
                        
    }
    
    //代理出价处理函数
    function AgentBid($goods_guid){
        
        $auctionGoods=AuctionGoods::findOne(['goods_guid'=>$goods_guid]);
        $maxAgentPrice=AuctionAgentBid::find()->andWhere(['goods_guid'=>$goods_guid,'is_valid'=>1])->max('top_price');
    
        //没有代理出价,直接返回
        if(empty($maxAgentPrice)){
            return false;
        }
        
        //当前最高出价为自己时,不做任何处理
        $auctionRec=AuctionBidRec::findOne(['goods_guid'=>$goods_guid,'is_leading'=>1]);
        if(!empty($auctionRec)&&$auctionRec->user_guid==yii::$app->user->identity->user_guid){
            return false;
        }
               
        //当前价格大于所有代理出价,则所有大代理出价均无效
        if($auctionGoods->current_price>=$maxAgentPrice){
            AuctionAgentBid::updateAll(['is_valid'=>0,'updated_at'=>time()],['goods_guid'=>$goods_guid]);
            return false;
        }
        
       $delta_price=20;
        if($auctionGoods->current_price>=200&&$auctionGoods->current_price<500){
            $delta_price=30;
        }elseif ($auctionGoods->current_price>=500&&$auctionGoods->current_price<1000){
            $delta_price=50;
        }elseif ($auctionGoods->current_price>=1000&&$auctionGoods->current_price<2000){
            $delta_price=100;
        }elseif ($auctionGoods->current_price>=2000&&$auctionGoods->current_price<5000){
            $delta_price=300;
        }elseif ($auctionGoods->current_price>=5000&&$auctionGoods->current_price<10000){
            $delta_price=500;
        }elseif($auctionGoods->current_price>=10000){
            $delta_price=1000;
        }
        
        $secondMaxAgentPrice=AuctionAgentBid::find()->andWhere(" goods_guid='$goods_guid' and is_valid=1 and top_price!=$maxAgentPrice ")->max('top_price');
        if (empty($secondMaxAgentPrice)){
            $bidPrice=intval($auctionGoods->current_price)+intval($delta_price) ;
        }else{
            $bidPrice=intval($secondMaxAgentPrice) + intval($delta_price);
        }
        
        $agentBid=AuctionAgentBid::find()->andWhere(" goods_guid ='$goods_guid' and is_valid=1 and top_price=$maxAgentPrice")->orderBy("created_at desc")->all();
        $countMax=count($agentBid);
        //只有一个最高代理价时,竞拍在第二高代理价的基础上增加一个幅度
        if($countMax==1){            
            $transaction=yii::$app->db->beginTransaction();
            try{
                AuctionBidRec::updateAll(['is_leading'=>0],['goods_guid'=>$goods_guid]);
                $bidRec=new AuctionBidRec();
                $bidRec->goods_guid=$goods_guid;
                $bidRec->user_guid=$agentBid[0]->user_guid;
                //最高代理价格小于系统保留价时,采用最高代理价
                if($maxAgentPrice<=$auctionGoods->lowest_deal_price){
                     $bidRec->price=$maxAgentPrice;  
                }else{
                    $bidRec->price=$bidPrice;
                }
                $bidRec->is_agent=1;
                $bidRec->is_leading=1;
                $bidRec->created_at=time();
                if(!$bidRec->save()) throw new Exception(" insert db auction_bid_rec error");            
                //更新拍品表
                $auctionGoods->count_auction+=1;
                $auctionGoods->current_price=$bidRec->price;
                $leading_user=$auctionGoods->leading_user;
                $auctionGoods->leading_user=$bidRec->user_guid;
                $auctionGoods->updated_at=time();
                if($auctionGoods->end_time - time() <=60){
                    $auctionGoods->end_time +=60;
                }
                if(!$auctionGoods->save()) throw new Exception(" insert db auction_goods error");                 
                $transaction->commit();
                $this->SendTemplateMessage($leading_user, $auctionGoods->id);
            }catch (Exception $e){
                $transaction->rollBack();
                new \Exception('出价失败');
                return false;
            }
        }else{
            //有多个最高代理价相同时,竞拍价格为最高代理价,按照后代理先出价的顺序进行出价
            $transaction=yii::$app->db->beginTransaction();
            try{
                foreach ($agentBid as $k=> $v){
                AuctionBidRec::updateAll(['is_leading'=>0],['goods_guid'=>$goods_guid]);
                $bidRec=new AuctionBidRec();
                $bidRec->goods_guid=$goods_guid;
                $bidRec->user_guid=$v->user_guid;
                $bidRec->price=$maxAgentPrice;
                $bidRec->is_agent=1;
                $bidRec->is_leading=1;
                $bidRec->created_at=time()+($k*35);
                if(!$bidRec->save()) throw new Exception(" insert db auction_bid_rec error");            
                //更新拍品表
                $leading_user=$auctionGoods->leading_user;
                $auctionGoods->count_auction+=1;
                $auctionGoods->current_price=$bidRec->price;
                $auctionGoods->leading_user=$bidRec->user_guid;
                $auctionGoods->updated_at=time();
                if(!$auctionGoods->save()) throw new Exception(" insert db auction_goods error");
                }
                $transaction->commit();
                $this->SendTemplateMessage($leading_user, $auctionGoods->id);
            }catch (Exception $e){
                
                $transaction->rollBack();
                new \Exception('出价失败');
                return false;
            }
        }

        $this->AutoBid($goods_guid);
        
        return true;
        
    }
    
    
    public function actionSubmitAgent(){
        $goods_guid=$_POST['goods-guid'];
        $user_guid=yii::$app->user->identity->user_guid;
        $top_price=$_POST['agent-price'];
        $auctionGoods=AuctionGoods::findOne(['goods_guid'=>$goods_guid]);
        $auctionAgentBid=new AuctionAgentBid();
        $auctionAgentBid->user_guid=$user_guid;
        $auctionAgentBid->top_price=$top_price;
        $auctionAgentBid->goods_guid=$goods_guid;
        $auctionAgentBid->created_at=time();
        if(!$auctionAgentBid->save()){
            yii::$app->getSession()->setFlash('error',"代理出价失败,请稍候重试!");
            return $this->redirect(yii::$app->request->referrer);
//             return $this->redirect(['view','id'=>$auctionGoods->id]);
        }
        
        yii::$app->getSession()->setFlash('success',"代理出价成功!");
        //如果是自己领先,则不做代理出价处理
        if($user_guid==$auctionGoods->leading_user){
            
            if($this->AutoBid($goods_guid)){
                yii::$app->getSession()->setFlash('success','代理出价成功!');
            }
            return $this->redirect(yii::$app->request->referrer);
//             return $this->redirect(['view','id'=>$auctionGoods->id]);
        }
        
        if($this->AgentBid($goods_guid)){
            yii::$app->getSession()->setFlash('success','代理出价成功!');
        }
        return $this->redirect(yii::$app->request->referrer);
//         return $this->redirect(['view','id'=>$auctionGoods->id]);
    }
    

  /*   public function actionPayGuarantee(){
        $order_guid=$_GET['order_guid'];
        
        //支付成功的处理逻辑
        $order=Order::findOne(['order_guid'=>$order_guid]);
        
        $trans=yii::$app->db->beginTransaction();
        try{            
            $order->is_pay=1;
            $order->pay_time=time();
            $order->updated_at=time();
            if(!$order->save()) throw new Exception("update order error");
            
            $guaranteeFee=GuaranteeFee::findOne(['fee_guid'=>$order->biz_guid]);
            $guaranteeFee->is_pay=1;
            $guaranteeFee->updated_at=time();
            if(!$guaranteeFee->save()) throw new Exception();
            
            $user=User::findOne(['user_guid'=>$order->user_guid]);
            
            $user->role_id=$guaranteeFee->user_role;
            if($user->role_id==3){
                $user->guarantee=1;
            }
            $user->updated_at=time();
            if(!$user->save()) throw new Exception();            
            $trans->commit();
        }catch(Exception $e){
            $trans->rollBack();
        }
               
        return $this->redirect(['site/pay-result','order_guid'=>$order_guid]);
        
    } */
    
    public function AutoBid($goods_guid){
        $auctionGoods=AuctionGoods::findOne(['goods_guid'=>$goods_guid]);
        //如果当前价格小于最低成交价格,系统自动出价
        if($auctionGoods->current_price<$auctionGoods->lowest_deal_price){
            $transaction=yii::$app->db->beginTransaction();
            try{
             $virtualUser=User::findOne(['role_id'=>0,'goods_guid'=>$goods_guid]);
            if(empty($virtualUser)){
              //新建虚拟用户
              $virtualUser=new User();
              $virtualUser->user_guid=CommonUtil::createUuid();
              $virtualUser->mobile=CommonUtil::getRandomMobile();
              $virtualUser->role_id=0;
              $virtualUser->goods_guid=$goods_guid;
              $virtualUser->img_path=yii::$app->params['virtualAvatarUrl'].rand(1, 20).'.png';
              $virtualUser->password=md5('123456');
              $virtualUser->created_at=time();
              if(!$virtualUser->save()) throw new Exception();
            }
    
                AuctionBidRec::updateAll(['is_leading'=>0],['goods_guid'=>$goods_guid]);
                
                $delta_price=20;
                if($auctionGoods->current_price>=200&&$auctionGoods->current_price<500){
                    $delta_price=30;
                }elseif ($auctionGoods->current_price>=500&&$auctionGoods->current_price<1000){
                    $delta_price=50;
                }elseif ($auctionGoods->current_price>=1000&&$auctionGoods->current_price<2000){
                    $delta_price=100;
                }elseif ($auctionGoods->current_price>=2000&&$auctionGoods->current_price<5000){
                    $delta_price=300;
                }elseif ($auctionGoods->current_price>=5000&&$auctionGoods->current_price<10000){
                    $delta_price=500;
                }elseif($auctionGoods->current_price>=10000){
                    $delta_price=1000;
                }
                //增加出价记录
                $bidRec=new AuctionBidRec();
                $bidRec->goods_guid=$goods_guid;
                $bidRec->user_guid=$virtualUser->user_guid;
                $bidRec->price=intval($auctionGoods->current_price)+intval($delta_price);
                $bidRec->is_leading=1;
                $bidRec->created_at=time()+1;
                if(!$bidRec->save()) throw new Exception(" insert db auction_bid_rec error");
    
                //更新拍品表
                $auctionGoods->count_auction+=1;
                $auctionGoods->current_price=$bidRec->price;
                $leading_user=$auctionGoods->leading_user;
                $auctionGoods->leading_user=$virtualUser->user_guid;
                $auctionGoods->updated_at=time();
                if($auctionGoods->end_time - time() <=60){
                    $auctionGoods->end_time +=60;
                }
                if(!$auctionGoods->save()) throw new Exception(" insert db auction_goods error");
    
                $transaction->commit();
                $this->SendTemplateMessage($leading_user, $auctionGoods->id);
                yii::$app->getSession()->setFlash('success','代理出价成功!');
                return  true;
    
            }catch (Exception $e){
                yii::$app->getSession()->setFlash('success','代理出价失败!');
                $transaction->rollBack();
                return false;
            }
    
        }
        return false;
    }

    protected function findModel($id)
    {
        if (($model = AuctionGoods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public  function SendTemplateMessage($user_guid,$goodsid){
       
        if(empty($user_guid)){
            return false;
        }
        $sendModel=new WeChatTemplate(yii::$app->params['appid'], yii::$app->params['appsecret']);
        $user=User::findOne(['user_guid'=>$user_guid]);
        $goods=AuctionGoods::findOne($goodsid);
        if($user->role_id==0){
            return false;
        }
        $data=[];
        $data['first']=[
            "value"=>'您参与的拍卖已经被超越,请及时出价!',
            "color"=>"#173177"
        ];
        $data['keyword1']=[
            "value"=>$goods->name,
            "color"=>"#173177"
        ];
        $data['keyword2']=[
            "value"=>$goods->current_price,
            "color"=>"#173177"
        ];
        $data['keyword3']=[
            "value"=>$goods->count_auction,
            "color"=>"#173177"
        ];
        $data['keyword4']=[
            "value"=>'拍卖中',
            "color"=>"#173177"
        ];
        $data['keyword5']=[
            "value"=>CommonUtil::fomatTime($goods->end_time),
            "color"=>"#173177"
        ];
        $result=false;
            $finalData=[
                "touser"=>$user->openid,
                "template_id"=>'RJL21kj3WHFNaj4bWaPjNupB3m0wAEdhcQITKiz9A2Y',
                "url"=>'http://wechat.1paibao.net/auction/view?id='.$goodsid,
                "topcolor"=>"#FF0000",
                "data"=>$data
            ];
            $res=$sendModel->send_template_message($finalData);
             
            if($res['errmsg']=='ok'){
                $result=true;
            }
        return $result;
    }
}
