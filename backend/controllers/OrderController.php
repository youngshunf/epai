<?php

namespace backend\Controllers;

use Yii;
use common\models\Order;
use common\models\SearchOrder;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\GuaranteeFee;
use common\models\WxpayRec;
use yii\db\Exception;
use common\models\WxpayRefundRec;
use yii\filters\AccessControl;
use common\models\AuctionGoods;

require_once "../../common/WxpayAPI/lib/WxPay.Api.php";
require_once '../../common/WxpayAPI/example/log.php';
/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
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
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchOrder();
        if(isset($_GET['roundid'])){
            $roundid=$_GET['roundid'];
            $goods=AuctionGoods::findAll(['roundid'=>$roundid]);
            $ids=[];
            foreach ($goods as $v){
                $ids[]=$v->goods_guid;
            }
            $searchModel->biz_guid=$ids;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    public function actionSendGoods(){
        $order=Order::findOne(['order_guid'=>$_POST['order_guid']]);
        $order->express_company=$_POST['company'];
        $order->express_number=$_POST['number'];
        $order->sent_time=time();
        $order->status=2;
        $order->updated_at=time();
        if($order->save()){
            yii::$app->getSession()->setFlash('success','发货成功!');
        }else{
            yii::$app->getSession()->setFlash('error','发货成功!');
        }
        
        return $this->redirect(yii::$app->request->referrer);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
       $this->findModel($id)->delete();
            yii::$app->getSession()->setFlash('success','订单已删除');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionCancel($id)
    {
        $model= $this->findModel($id);
        $model->status=99;
        if($model->save()){
            yii::$app->getSession()->setFlash('success','订单已取消');
        }
        return $this->redirect(yii::$app->request->referrer);
    }
    
    //退还保证金
    public function actionGuaranteeRefund($id){
        $guaranteeFee=GuaranteeFee::findOne($id);
        $order=Order::findOne(['biz_guid'=>$guaranteeFee->fee_guid,'type'=>Order::TYPE_GUARANTEE,'is_pay'=>1]);
        if(empty($order)){
            yii::$app->getSession()->setFlash('error','订单不存在或未支付');
            return $this->redirect(yii::$app->request->referrer);
        }
        $wxpayRec=WxpayRec::findOne(['appid'=>yii::$app->params['appid'],'attach'=>$order->order_guid,'out_trade_no'=>$order->orderno]);

        if(empty($wxpayRec)){
            yii::$app->getSession()->setFlash('error','订单不存在或未支付!');
            return $this->redirect(yii::$app->request->referrer);
        }
        
        $input = new \WxPayRefund();
        $input->SetOut_trade_no($wxpayRec->out_trade_no);
        $input->SetTotal_fee($wxpayRec->total_fee);
        $input->SetRefund_fee($wxpayRec->total_fee);
        $input->SetOp_user_id(\WxPayConfig::MCHID);
        $wxpayRec->out_refund_no=\WxPayConfig::MCHID.date('YmdHis');
        $input->SetOut_refund_no( $wxpayRec->out_refund_no);
        $result=\WxPayApi::refund($input,2000);
        if($result['return_code']=='SUCCESS'&&$result['result_code']=="SUCCESS"){
            $trans=yii::$app->db->beginTransaction();
            try{
              $wxpayRec->is_refund=1;
            $wxpayRec->refund_id=@$result['refund_id'];
            //$wxpayRec->refund_channel=@$result['refund_channel'];
            $wxpayRec->refund_fee=@$result['refund_fee'];
            $wxpayRec->refund_time=time();
            $wxpayRec->updated_at=time();
            if(!$wxpayRec->save()) throw new Exception();
            
            $guaranteeFee->status=2;
            $guaranteeFee->updated_at=time();
            if(!$guaranteeFee->save()) throw new Exception();
            
            $order->status=98;
            $order->updated_at=time();
            if(!$order->save()) throw new Exception();
            
            $wxpayRefundRec=new WxpayRefundRec();
            $wxpayRefundRec->refund_id=@$result['refund_id'];
            $wxpayRefundRec->out_refund_no=@$result['out_refund_no'];
            $wxpayRefundRec->out_trade_no=@$result['out_trade_no'];
            $wxpayRefundRec->appid=@$result['appid'];
            $wxpayRefundRec->mch_id=@$result['mch_id'];
            $wxpayRefundRec->device_info=@$result['device_info'];
            $wxpayRefundRec->nonce_str=@$result['nonce_str'];
            $wxpayRefundRec->transaction_id=@$result['transaction_id'];
           // $wxpayRefundRec->refund_channel=@$result['refund_channel'];
            $wxpayRefundRec->refund_fee=@$result['refund_fee'];
            $wxpayRefundRec->total_fee=@$result['total_fee'];
            $wxpayRefundRec->cash_fee=@$result['cash_fee'];
            $wxpayRefundRec->cash_refund_fee=@$result['cash_refund_fee'];
            $wxpayRefundRec->coupon_refund_fee=@$result['coupon_refund_fee'];
            $wxpayRefundRec->coupon_refund_count=@$result['coupon_refund_count'];
            $wxpayRefundRec->coupon_refund_id=@$result['coupon_refund_id'];
            $wxpayRefundRec->created_at=time();
            if(!$wxpayRefundRec->save()) throw new Exception();
            
            $trans->commit();
            yii::$app->getSession()->setFlash('success','退款成功!');
            return $this->redirect(yii::$app->request->referrer);
            }catch (Exception $e){
                $trans->rollBack();
                yii::$app->getSession()->setFlash('error','退款失败!');
                return $this->redirect(yii::$app->request->referrer);
            }
        }
        
        yii::$app->getSession()->setFlash('error','退款失败!');
        return $this->redirect(yii::$app->request->referrer);
        
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
