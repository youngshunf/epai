<?php
namespace wechat\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use wechat\models\LoginForm;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\News;
use common\models\HomePhoto;
use common\models\Siteinfo;
use common\models\AuctionGoods;
use common\models\Order;
use yii\db\Exception;
use common\models\LotteryGoods;
use common\models\LotteryRec;
use common\models\CommonUtil;
use common\models\MallGoods;
use wechat\models\WeChatLogin;
use common\models\User;
use wechat\models\BandForm;
use common\models\PayNotifyCallBack;
use common\models\GuaranteeFee;
use common\models\AuctionRound;
use common\models\Message;
use common\models\RegisterCoupon;
use common\models\Coupon;

require_once "../../common/WxpayAPI/lib/WxPay.Api.php";
require_once "../../common/WxpayAPI/example/WxPay.JsApiPay.php";
require_once '../../common/WxpayAPI/example/log.php';
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
                        'actions' => ['login','login-do','pay-notify','index'],
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
              /*   'actions' => [
                    'logout' => ['post'],
                ], */
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
    
/*     public function beforeAction($action){
        if($action->id!="login" || $action->id!="login-do" ||  $action->id!="band-user"  ){
            yii::$app->getUser()->setReturnUrl(yii::$app->getRequest()->getAbsoluteUrl());
        }  
        
       if(!yii::$app->user->isGuest){
            if(!empty(yii::$app->user->identity->openid)&&yii::$app->user->identity->is_band==0){
                return $this->redirect(['site/band']);
            }
        } 
        
        return parent::beforeAction($action);
    } */

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
     
     $auction=AuctionGoods::find()->andWhere(" status=1 ")->orderBy('created_at desc')->limit(12)->all();
     
     $preview=AuctionGoods::find()->andWhere(" status=0")->orderBy('created_at desc')->limit(12)->all();
     
     $round=AuctionRound::find()->orderBy('start_time desc')->limit(7)->all();
     
     
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
    
    public function actionMortage(){
        $model=Siteinfo::findOne(['id'=>2]);   
        $this->layout="@frontend/views/layouts/site_layout.php";
        return $this->render('mortage',['model'=>$model]);
    }
    
   public function actionParamError(){
       return $this->render('param-error');
   }
   
   public  function  actionBandUser(){
     
       $model=new BandForm();
       
       if($model->load(yii::$app->request->post())){
           $user=User::findOne(['mobile'=>$model->username]);
           if(!empty($user)){
               $password=md5($model->password);
               if($user->password!=$password){
                   yii::$app->getSession()->setFlash('error',"密码错误！您的手机号已注册过E拍宝网站,请填写您的登录密码！");
                   return $this->render('band',['model'=>$model]);
               }
               if($user->is_band==1){
                   yii::$app->getSession()->setFlash('error',"该手机号已被其他微信号绑定,请不要重复绑定！");
                   return $this->render('band',['model'=>$model]);
               }
               
               $trans=yii::$app->db->beginTransaction();
               try{
               $currentUser=User::findOne(yii::$app->user->identity->id);
               $currentUser->user_guid=$user->user_guid;
               $currentUser->mobile=$user->mobile;
               $currentUser->password=$user->password;
               $currentUser->setPassword($model->password);
               $currentUser->email=$user->email;
               $currentUser->is_band=1;
               $currentUser->updated_at=time();
         
               if(!$currentUser->save()) throw  new Exception();
               
               if($user->openid!=$currentUser->openid){
                  if(!$user->delete()) throw new Exception();
               }
               
               $trans->commit();
               yii::$app->getSession()->setFlash('success',"绑定成功！");
               return  $this->redirect(['auction/index']);
               
               }catch (Exception $e){
                   $trans->rollBack();
                   yii::$app->getSession()->setFlash('error',"绑定失败！");
                   return $this->render('band',['model'=>$model]);
               }
           }else{
               $currentUser=User::findOne(yii::$app->user->identity->id);
               $currentUser->mobile=$model->username;
               $currentUser->password=md5($model->password);
               $currentUser->setPassword($model->password);
               $currentUser->is_band=1;
               $currentUser->updated_at=time();
             
               if($currentUser->save()){
                   //发放注册优惠
                   $registerCoupon=RegisterCoupon::find()->andWhere(['is_open'=>1])->one();
                   if(!empty($registerCoupon)){
                       $coupon=new Coupon();
                       $coupon->coupon_code=Coupon::generateCouponCode();
                       $coupon->user_guid=$currentUser->user_guid;
                       $coupon->amount=$registerCoupon->amount;
                       $coupon->min_amount=$registerCoupon->min_amount;
                       $coupon->end_time=strtotime("+".$registerCoupon->expire_day." day");
                       $coupon->type=1;
                       $coupon->remark=$registerCoupon->remark;
                       $coupon->status=1;
                       $coupon->get_time=time();
                       $coupon->created_at=time();
                       $coupon->save();
                   }
                   
                   yii::$app->getSession()->setFlash('success',"绑定成功！");
                   return  $this->redirect(['auction/index']);
               }
           }
           
           
       }else{
           return $this->render('band',['model'=>$model]);
       }
       
       
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
           $jsApiParameters=array();
           if($order->is_pay==0){
           //初始化日志
           $logHandler= new \CLogFileHandler("../runtime/logs/".date('Y-m-d').'.log');
           $log = \Log::Init($logHandler, 15);
           //①、获取用户openid
           $tools = new \JsApiPay();
           $openId = $tools->GetOpenid(yii::$app->request->absoluteUrl);
           //②、统一下单
           $input = new \WxPayUnifiedOrder();
           $input->SetBody($order->goods_name);
           $input->SetAttach($order->order_guid);
           $input->SetOut_trade_no($order->orderno);
           // $input->SetTotal_fee($order->support_amount*100);
           $input->SetTotal_fee($order->amount*100);
           $input->SetTime_start(date("YmdHis"));
           $input->SetTime_expire(date("YmdHis", time() + 600));
           $input->SetGoods_tag(yii::$app->user->identity->nick);
           $input->SetNotify_url(yii::$app->params['paynotify']);
           $input->SetTrade_type("JSAPI");
           $input->SetOpenid($openId);
           $wxorder = \WxPayApi::unifiedOrder($input);
           $jsApiParameters = $tools->GetJsApiParameters($wxorder);
       }
       
       return $this->render('pay-order',['order'=>$order,
           'jsApiParameters'=>$jsApiParameters
       ]);
   }
   
   public function actionPayDo(){
       $order_guid=$_GET['order_guid'];
       $order=Order::findOne(['order_guid'=>$order_guid]);
       $trans=yii::$app->db->beginTransaction();
       $user_guid=yii::$app->user->identity->user_guid;
       try{
        $order->is_pay=1;
        if($order->type==Order::TYPE_GUARANTEE ||$order->type==Order::TYPE_LOTTERY){
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
                $lotteryGoods->status=2;
              $lotteryAward=LotteryRec::findOne(['goods_guid'=>$order->biz_guid,'is_award'=>1]);
                if(empty($lotteryAward)){//开始抽奖
                $lotteryLib=LotteryRec::findAll(['goods_guid'=>$order->biz_guid]);
                $lottery_id=$lotteryLib[rand(0, intval(count($lotteryLib)-1))]['id'];
                $lottery=LotteryRec::findOne($lottery_id);
                $lottery->is_award=1;
                $lottery->award_time=time();
                if(!$lottery->save()) throw new Exception();
                $lotteryAward=$lottery;
                }
                //发送中奖通知
                $message=new Message();
                $content="您好!您参与的一元夺宝-<span class='red'>".$lotteryGoods->name."</span>-已揭晓,恭喜您中奖了。中奖号码: <span class='red'>".$lotteryAward->lottery_code."</span>
                    <a class='btn btn-success' href='".yii::$app->urlManager->createAbsoluteUrl(['lottery/view','id'=>$lotteryGoods->id])."'>查看详情</a>";
                $message->send(null,$lotteryAward->user_guid, $content, Message::SYS);    
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
               $goods->status=3;
               $goods->deal_price=$order->amount;
               $goods->updated_at=time();
               if(!$goods->save()) throw new Exception();
        }elseif ($order->type==Order::TYPE_GUARANTEE){
                $guaranteeFee=GuaranteeFee::findOne(['fee_guid'=>$order->biz_guid]);
                $guaranteeFee->is_pay=1;
                $guaranteeFee->status=1;
                $guaranteeFee->updated_at=time();
                if(!$guaranteeFee->save()) throw new Exception();
            
                $user=User::findOne(['user_guid'=>$order->user_guid]);
            
                $user->role_id=$guaranteeFee->user_role;
                if($user->role_id==3){
                    $user->guarantee=1;
                }
                $user->updated_at=time();
                if(!$user->save()) throw new Exception();
           
        }          
        
        $trans->commit();
       }catch (Exception $e){
           $trans->rollBack();       
       }     
       return $this->redirect(['pay-result','order_guid'=>$order_guid]);
   }
   
   public function actionPayNotify(){
       $logHandler= new \CLogFileHandler("../../common/WxpayAPI/logs/".date('Y-m-d').'.log');
       $log = \Log::Init($logHandler, 15);
       \Log::DEBUG(date('Y-m-d H:i:s').":get notify");
       $notify= new PayNotifyCallBack();
       $notify->Handle(false);
   }
   
   public function actionPayResult($order_guid){
       $order=Order::findOne(['order_guid'=>$order_guid]);
       return $this->render('pay-result',['order'=>$order]);
   }
   
 
   
    public function actionAddUserProfile(){

         //    return $this->render('auth',['model'=>$model]);
    }
    
    public function actionNoticeView($id){
    
    }

/*      public function actionLogin()
    {
     $model=new LoginForm();
    
     if($model->load(Yii::$app->request->post())&&$model->login()){
         return $this->goBack();
     }
         
     return $this->render('login',['model'=>$model]);
    }   */
    
    /**
     * 认证服务号登录,网页授权登录
     * @return \yii\web\Response
     */
     public function actionLogin()
    {
        $appid=yii::$app->params['appid'];
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=http://wechat.1paibao.net/site/login-do&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
        return $this->redirect($url);
    }  
    
    /**
     * 认证服务号登录处理
     * @return \yii\web\Response
     */
    public function actionLoginDo(){
        if(!isset($_GET['code'])){
            return $this->redirect(['site/param-error']);
        }
    
        $code=$_GET['code'];
        $model=new WeChatLogin();
        if($model->Login($code)){
            //return $this->redirect(['subject/choose-lib']);
            return $this->goBack();
        }
    
        return $this->redirect(['site/login-fail']);
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
