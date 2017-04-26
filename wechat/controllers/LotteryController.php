<?php

namespace wechat\controllers;

use Yii;
use common\models\LotteryGoods;
use common\models\SearchLotteryGoods;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\CommonUtil;
use yii\filters\AccessControl;
use common\models\Order;
use common\models\LotteryRec;
use yii\data\ActiveDataProvider;
use common\models\Address;


/**
 * LotteryController implements the CRUD actions for LotteryGoods model.
 */
class LotteryController extends Controller
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
        if($action->id=='index' || $action->id=='view'){
            yii::$app->getUser()->setReturnUrl(yii::$app->getRequest()->getAbsoluteUrl());
        }
     if(!yii::$app->user->isGuest){
            if(!empty(yii::$app->user->identity->openid)&&yii::$app->user->identity->is_band==0){
                  return $this->redirect(['site/band-user']);
            }
        }
        
        return parent::beforeAction($action);
    }
    /**
     * Lists all LotteryGoods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $now=time();
        LotteryGoods::updateAll(['status'=>1],"status=0 and $now>end_time ");
        
        $status=0;
        if(isset($_GET['status'])){
            $status=$_GET['status'];
        }
        $searchModel = new SearchLotteryGoods();
        $searchModel->status=$status;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status'=>$status
        ]);
    }

    /**
     * Displays a single LotteryGoods model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model=$this->findModel($id);
        $model->count_view+=1;
        $model->save();
        return $this->render('view', [
            'model' =>$model ,
        ]);
    }

  public function actionBuy($id){
      $model=$this->findModel($id);
      return $this->render('buy', [
          'model' => $model,
      ]);
  }
  
  public function actionLotteryRec($goods_guid){
      $recData=new ActiveDataProvider([
          'query'=>LotteryRec::find()->andWhere(['goods_guid'=>$goods_guid])->groupBy('user_guid')->orderBy("created_at desc"),
          'pagination'=>[
              'pagesize'=>10
          ]
      ]);
      
      return $this->render('lottery-rec',['recData'=>$recData]);
  }
  
  /**
   * 提交订单
   * @return \yii\web\Response
   */
  public function actionSubmitOrder(){
      $goods_guid=$_POST['goods-guid'];
      $number=$_POST['number'];
      $user_guid=yii::$app->user->identity->user_guid;
      $lotteryGoods=LotteryGoods::findOne(['goods_guid'=>$goods_guid]);  
      
      //获取用户默认收货地址
      $address=Address::findOne(['user_guid'=>$user_guid,'is_default'=>1]);
      if(empty($address)){
          yii::$app->getSession()->setFlash('error','对不起,你没有设置默认收货地址!');
          return $this->redirect(yii::$app->request->referrer);
      }
      
      $order=new Order();
      $order->order_guid=CommonUtil::createUuid();
      $order->user_guid=$user_guid;
      $order->biz_guid=$goods_guid;
      $order->orderno=Order::getOrderNO(Order::TYPE_LOTTERY);
      $order->type=Order::TYPE_LOTTERY;
      $order->amount=$number;
      $order->number=$number;
      $order->goods_name=$lotteryGoods->name;
      $order->address_id=$address->id;
      $order->address=$address['province'].' '.$address['city'].' '.$address['district'].' '.$address['address'].' '.$address['company'].' '.$address['name'].' '.$address['phone'];
      $order->created_at=time();     
      if(!$order->save()){
          yii::$app->getSession()->setFlash('error','提交订单失败!');
          return $this->redirect(yii::$app->getRequest()->referrer);
      }     

      return $this->redirect(['site/pay-order','order_guid'=>$order->order_guid]);
  }
  
  
    protected function findModel($id)
    {
        if (($model = LotteryGoods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
