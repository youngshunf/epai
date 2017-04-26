<?php
namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use frontend\models\LoginForm;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\News;
use common\models\HomePhoto;
use common\models\Siteinfo;
use common\models\AuctionGoods;
use common\models\Order;
use common\models\AuctionRound;
use common\models\MallGoods;
use yii\db\Exception;
use common\models\LotteryRec;
use common\models\CommonUtil;
use common\models\LotteryGoods;
use common\models\GuaranteeFee;
use common\models\User;

use dosamigos\qrcode\QrCode;
use dosamigos\qrcode\lib\Enum;
use common\models\NavtivePayNotifyCallBack;
use common\models\Message;

require_once "../../common/WxpayAPI/lib/WxPay.Api.php";
require_once "../../common/WxpayAPI/example/WxPay.NativePay.php";
require_once '../../common/WxpayAPI/example/log.php';
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
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
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
              /*   'actions' => [
                    'logout' => ['post'],
                ], */
            ], 
         [
            'class' => 'yii\filters\PageCache',
            'only' => ['collect','contact'],       
            'variations' => [
                \Yii::$app->language,
            ],
            'dependency' => [
                'class' => 'yii\caching\DbDependency',
                'sql' => 'SELECT updated_at FROM siteinfo',
            ],
         ],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function beforeAction($action){
    
        if($action->id=="login"){
          if(!yii::$app->getRequest()->isPost){
              yii::$app->getUser()->setReturnUrl(yii::$app->getRequest()->getReferrer());
          }
           
        }       
        return parent::beforeAction($action);
    }


    public function actionIndex()   
    {      
        
    $homePhoto=HomePhoto::find()->orderBy('created_at desc')->all();
    //新闻资讯(今日潘家园)
     $news=News::find()->andWhere(['cateid'=>1])->orderBy('created_at desc')->limit(10)->all();
     
     //易宝天下
     $treasures=News::find()->andWhere(['cateid'=>2])->orderBy('created_at desc')->limit(5)->all();
     //知文玩
     $knowledges=News::find()->andWhere(['cateid'=>3])->orderBy('created_at desc')->limit(5)->all();
     
     $now=time();
     
     $auction=AuctionGoods::find()->andWhere(" status=1 and auth_status=1 and post_type=1")->orderBy('sort desc,created_at asc')->limit(12)->all();
     
     $preview=AuctionGoods::find()->andWhere(" start_time > $now and auth_status=1 and post_type=1 ")->orderBy('sort desc,created_at asc')->limit(12)->all();
     
     $round=AuctionRound::find()->andWhere(" $now <= end_time and auth_status=1 ")->orderBy('sort desc,created_at asc')->limit(7)->all();
     
      return $this->render('index',[
          'news'=>$news,
          'homePhoto'=>$homePhoto,
          'treasures'=>$treasures,
          'knowledges'=>$knowledges,
          'auction'=>$auction,
          'preview'=>$preview,
          'round'=>$round
      ]);
      
    }
    
    public function actionAddTemplate(){
        $templateData=$_GET['userStr'];
        $result=[
            'result'=>0,
            'templateData'=>$templateData
        ];
        return json_encode($result);
    }
    
    
    public function actionCollect(){
        $model=Siteinfo::findOne(['id'=>1]);
        $this->layout="@frontend/views/layouts/site_layout.php";
        return $this->render('collect',['model'=>$model]);
    }
    
    public function actionContact(){
        $model=Siteinfo::findOne(['id'=>3]);
        
        $this->layout="@frontend/views/layouts/site_layout.php";
        return $this->render('contact',['model'=>$model]);
    }
    
    public function actionAuctionRules(){
        $model=Siteinfo::findOne(['id'=>4]);
    
        $this->layout="@frontend/views/layouts/site_layout.php";
        return $this->render('auction-rules',['model'=>$model]);
    }
    
   public function actionParamError(){
       return $this->render('param-error');
   }
   
   public function actionNoAuth(){
       return $this->render('no-auth');
   }
   
   public function actionAuthSuccess(){
       return $this->render('auth-success');
   }
   
   public function actionLoginFail(){
       return $this->render('login-fail');
   }
   
   public function actionPayOrder($order_guid){
       $order=Order::findOne(['order_guid'=>$order_guid]);
       //模式二
       /**
        * 流程：
        * 1、调用统一下单，取得code_url，生成二维码
        * 2、用户扫描二维码，进行支付
        * 3、支付完成之后，微信服务器会通知支付成功
        * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
        */
       $notify = new \NativePay();
       $input = new \WxPayUnifiedOrder();
       $input->SetBody($order->goods_name);
       $input->SetAttach($order->order_guid);
       $input->SetOut_trade_no($order->orderno);
       //$input->SetTotal_fee("1");
       $input->SetTotal_fee($order->amount*100);
       $input->SetTime_start(date("YmdHis"));
       $input->SetTime_expire(date("YmdHis", time() + 600));
       $input->SetGoods_tag(yii::$app->user->identity->mobile);
       $input->SetNotify_url(yii::$app->params['native-pay-notify']);
       $input->SetTrade_type("NATIVE");
       $input->SetProduct_id('123456');
       $result = $notify->GetPayUrl($input);
       $payUrl = $result["code_url"];
       
       //二维码生成
       $qrPath='../../upload/photo/qrcode/';
       if(!is_dir($qrPath)){
           mkdir($qrPath);
       }
       $qrFile=$qrPath.$order->order_guid.'.png';
       if(!file_exists($qrFile)){
           QrCode::png($payUrl,$qrFile,Enum::QR_ECLEVEL_H,7);
       }
       return $this->render('pay-order',['order'=>$order]);
   }
   
   public function actionCheckOrder(){
       $order_guid=$_POST['order_guid'];
       $order=Order::findOne(['order_guid'=>$order_guid]);
       if(!empty($order)&&$order->is_pay==1){
           return "paid";
       }
       return "no-pay";
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
       
     
       
       
       //模式二
       /**
        * 流程：
        * 1、调用统一下单，取得code_url，生成二维码
        * 2、用户扫描二维码，进行支付
        * 3、支付完成之后，微信服务器会通知支付成功
        * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
       */
//         $notify = new \NativePay();
//         $input = new \WxPayUnifiedOrder();
//        $input->SetBody($order->goods_name);
//        $input->SetAttach($order->order_guid);
//        $input->SetOut_trade_no($order->orderno);
//        $input->SetTotal_fee("1");
//        $input->SetTime_start(date("YmdHis"));
//        $input->SetTime_expire(date("YmdHis", time() + 600));
//        $input->SetGoods_tag(yii::$app->user->identity->mobile);
//        $input->SetNotify_url(yii::$app->params['pay-guarantee-notify']);
//        $input->SetTrade_type("NATIVE");
//        $input->SetProduct_id('123456');
//        $result = $notify->GetPayUrl($input);
//        $payUrl = $result["code_url"];

//        //二维码生成
//        $qrPath='../../upload/photo/qrcode/';
//        if(!is_dir($qrPath)){
//            mkdir($qrPath);
//        }
//        $qrFile=$qrPath.$order->order_guid.'.png';
//        if(!file_exists($qrFile)){
//            QrCode::png($payUrl,$qrFile,Enum::QR_ECLEVEL_H,7);
//        }
   
       return $this->redirect(["pay-order",
           'order_guid'=>$order->order_guid,
       ]);
   }
   
   public function actionPayGuarantee(){
       $order_guid=$_POST['order-guid'];
   
       //模拟支付成功的处理逻辑
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
           /*           yii::$app->getSession()->setFlash('error',"支付失败!");
            return $this->redirect(['site/pay-result','order_guid'=>$order_guid]); */
       }
       return $this->redirect(['site/pay-result','order_guid'=>$order_guid]);
   
   }
   
   public function actionPayDo(){
       $order_guid=$_POST['order-guid'];
       $order=Order::findOne(['order_guid'=>$order_guid]);
       $trans=yii::$app->db->beginTransaction();
       $user_guid=yii::$app->user->identity->user_guid;
       try{
           $order->is_pay=1;
           if($order->type==Order::TYPE_GUARANTEE){
               $order->status=3;
           }else{
               $order->status=1;
           }
           $order->pay_time=time();
           $order->updated_at=time();
           if(!$order->save()) throw new Exception("订单更新失败!");
   
           if($order->type==Order::TYPE_LOTTERY){
               $lotteryGoods=LotteryGoods::findOne(['goods_guid'=>$order->biz_guid]);
   
               for( $i=0;$i<$order->number;$i++){
                   $lotteryRec=new LotteryRec();
                   $lotteryRec->goods_guid=$order->biz_guid;
                   $lotteryRec->order_guid=$order_guid;
                   $lotteryRec->user_guid=$user_guid;
                   $lotteryRec->lottery_code=LotteryRec::getLotteryCode();
                   $lotteryRec->ip=CommonUtil::getClientIp();
                   $lotteryRec->created_at=time();
                   if(!$lotteryRec->save() ) throw new Exception();
               }
   
               $lotteryGoods->count_lottery+=$order->number;
               if($lotteryGoods->count_lottery>=$lotteryGoods->price){
   
                   //开始抽奖
                   $lotteryLib=LotteryRec::findAll(['goods_guid'=>$order->biz_guid]);
                   $lottery_id=$lotteryLib[rand(0, intval(count($lotteryLib)-1))]['id'];
                   $lottery=LotteryRec::findOne($lottery_id);
                   $lottery->is_award=1;
                   $lottery->award_time=time();
                   if(!$lottery->save()) throw new Exception();
                   $lotteryGoods->status=2;
               }
   
               $lotteryGoods->updated_at=time();
               if(!$lotteryGoods->save()) throw new Exception();
   
           }elseif ($order->type==Order::TYPE_MALL){
               $goods=MallGoods::findOne(['goods_guid'=>$order->biz_guid]);
               $goods->count_sales +=$order->number;
               $goods->updated_at=time();
               if(!$goods->save()) throw new Exception();
           }elseif ($order->type==Order::TYPE_AUCTION){
               $goods=AuctionGoods::findOne(['goods_guid'=>$order->biz_guid]);
               $goods->deal_user=$order->user_guid;
               $goods->status=2;
               $goods->deal_price=$order->amount;
               $goods->updated_at=time();
               if(!$goods->save()) throw new Exception();
           }
   
           $trans->commit();
       }catch (Exception $e){
           $trans->rollBack();
       }
       return $this->redirect(['pay-result','order_guid'=>$order_guid]);
   }
   
   //保证金处理
/*    public function actionPayGuaranteeNotify(){
     
       $logHandler= new \CLogFileHandler("../../common/WxpayAPI/logs/".date('Y-m-d').'.log');
       $log = \Log::Init($logHandler, 15);
       \Log::DEBUG(date('Y-m-d H:i:s').":get notify");
       $notify= new PayGuranteeNotifyCallBack();
       $notify->Handle(false);
   } */
   
   //微信支付订单处理
   public function actionPayNotify(){
       $logHandler= new \CLogFileHandler("../../common/WxpayAPI/logs/".date('Y-m-d').'.log');
       $log = \Log::Init($logHandler, 15);
       \Log::DEBUG(date('Y-m-d H:i:s').":get notify");
       $notify= new NavtivePayNotifyCallBack();
       $notify->Handle(false);
   }
   
   
    
   public function actionPayResult($order_guid){
       $order=Order::findOne(['order_guid'=>$order_guid]);
       return $this->render('pay-result',['order'=>$order]);
   }
   
   public function actionHomeView($id){
       $model=HomePhoto::findOne($id);
       return $this->render('home-view',['model'=>$model]);
   }
   
    public function actionAddUserProfile(){

         //    return $this->render('auth',['model'=>$model]);
    }
    
    public function actionNoticeView($id){
    
    }

   public function actionLogin()
    {
        $model=new LoginForm();
        
        if($model->load(yii::$app->request->post())&&$model->login()){
            return $this->goBack();
        }
        
        return $this->render('login',['model'=>$model]);
    }
     


    
    public function actionRegister(){
    
        $model = new \frontend\models\RegisterForm();
 
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
             
        }elseif($model->load(Yii::$app->request->post())){
            
            if($model->register()){
                $login=new LoginForm();
                $login->username=$model->mobile;
                $login->password=$model->password;
                if($login->login()){
                    yii::$app->getSession()->setFlash("success","注册成功,已为您自动登陆!");
                    return $this->goHome();
                }
                
            }    
      
    
        } else {
            return $this->render('register', [
                'model' => $model,
            ]);
        }
         
    }
    
    public function actionLogout(){
      Yii::$app->user->logout(false);

        return $this->goHome();
    	
    } 
       
     
   
    
}
