<?php

namespace backend\controllers;

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
use common\models\CommonUtil;
use common\models\Address;

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
        $roundid='';
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
            'roundid'=>$roundid
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
    
    public function actionExportOrder(){
        if(!empty($_GET['roundid'])){
            $roundid=$_GET['roundid'];
            $goods=AuctionGoods::findAll(['roundid'=>$roundid]);
            $ids=[];
            foreach ($goods as $v){
                $ids[]=$v->goods_guid;
            }
            $model=Order::find()->andWhere(['biz_guid'=>$ids])->all();
        }else{
            $model=Order::find()->all();
        }
        
        if(empty($model)){
            yii::$app->getSession()->setFlash('error','没有数据哦!');
            return $this->redirect(yii::$app->getRequest()->referrer);
        }
        
        $resultExcel=new \PHPExcel();
        $resultExcel->getActiveSheet()->setCellValue('A1','序号');
        $resultExcel->getActiveSheet()->setCellValue('B1','是否支付');
        $resultExcel->getActiveSheet()->setCellValue('C1','收货人');
        $resultExcel->getActiveSheet()->setCellValue('D1','收货人');
        $resultExcel->getActiveSheet()->setCellValue('E1','收货电话');
        $resultExcel->getActiveSheet()->setCellValue('F1','收货电话');
        $resultExcel->getActiveSheet()->setCellValue('G1','收货地址');
        $resultExcel->getActiveSheet()->setCellValue('H1','商品名称');
        $resultExcel->getActiveSheet()->setCellValue('I1','已支付金额');
        $resultExcel->getActiveSheet()->setCellValue('J1','待支付金额');
        $resultExcel->getActiveSheet()->setCellValue('K1','电话');
        $resultExcel->getActiveSheet()->setCellValue('L1','状态');
        $resultExcel->getActiveSheet()->setCellValue('M1','支付时间');
        $resultExcel->getActiveSheet()->setCellValue('N1','订单编号');
        $resultExcel->getActiveSheet()->setCellValue('O1','数量');
        $resultExcel->getActiveSheet()->setCellValue('P1','快递公司');
        $resultExcel->getActiveSheet()->setCellValue('Q1','快递单号');
        $resultExcel->getActiveSheet()->setCellValue('R1','订单时间');
        
        $i=2;
        foreach ($model as $k=>$v){
            $resultExcel->getActiveSheet()->setCellValue('A'.$i,$k+1);
            $resultExcel->getActiveSheet()->setCellValue('B'.$i,CommonUtil::getDescByValue('order', 'is_pay', $v->is_pay));
            $address=Address::findOne($v->address_id);
            if(!empty($address)){
                $resultExcel->getActiveSheet()->setCellValue('C'.$i,$address->name);
                $resultExcel->getActiveSheet()->setCellValue('D'.$i,$address->name);
                $resultExcel->getActiveSheet()->setCellValue('E'.$i,$address->phone);
                $resultExcel->getActiveSheet()->setCellValue('F'.$i,$address->phone);
                $resultExcel->getActiveSheet()->setCellValue('G'.$i,$address->province.$address->city.$address->district.$address->address);
            }
            $resultExcel->getActiveSheet()->setCellValue('H'.$i,$v->goods_name);
            $resultExcel->getActiveSheet()->setCellValue('I'.$i,$v->is_pay==1?$v->amount:0);
            $resultExcel->getActiveSheet()->setCellValue('J'.$i,$v->is_pay==1?0:$v->amount);
            $resultExcel->getActiveSheet()->setCellValue('K'.$i,$v->user->mobile);
            $resultExcel->getActiveSheet()->setCellValue('L'.$i,CommonUtil::getDescByValue('order', 'status', $v->status));
            $resultExcel->getActiveSheet()->setCellValue('M'.$i,CommonUtil::fomatTime($v->pay_time));
            $resultExcel->getActiveSheet()->setCellValue('N'.$i,$v->orderno);
            $resultExcel->getActiveSheet()->setCellValue('O'.$i,$v->number);
            $resultExcel->getActiveSheet()->setCellValue('P'.$i,$v->express_company);
            $resultExcel->getActiveSheet()->setCellValue('Q'.$i,$v->express_number);
            $resultExcel->getActiveSheet()->setCellValue('R'.$i,CommonUtil::fomatTime($v->created_at));
            $i++;
        }
        
        //设置导出文件名
        $outputFileName ="拍卖订单".date('Y-m-d',time()).'.xls';
        $xlsWriter = new \PHPExcel_Writer_Excel5($resultExcel);
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outputFileName.'"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        
        $xlsWriter->save( "php://output" );
        
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
