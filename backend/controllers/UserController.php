<?php

namespace backend\Controllers;

use Yii;
use common\models\User;
use common\models\SearchUser;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use backend\models\AdminUser;
use common\models\CommonUtil;
use common\models\VipRefund;
use common\models\GuaranteeFee;
use common\models\Order;
use common\models\WxpayRec;
use yii\db\Exception;
use common\models\WxpayRefundRec;
use common\models\IdPhoto;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    public function beforeAction($action){
        $this->layout="@app/views/layouts/user_layout.php";
        return parent::beforeAction($action);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchUser();
     /*    $searchModel->role_id!=0; */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionAdmin(){
        $dataProvider= new ActiveDataProvider([
            'query'=>AdminUser::find()->orderBy('created_at desc'),
        ]);
        
        return $this->render('admin',[
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @param string $openid
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    public function actionVipRefund(){
        $dataProvider=new ActiveDataProvider([
            'query'=>VipRefund::find()->orderBy('created_at desc')
        ]);
        
        return $this->render('vip-refund',[
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionMerchantAuth(){
        $dataProvider=new ActiveDataProvider([
            'query'=>IdPhoto::find()->groupBy('user_guid')->orderBy('created_at desc')
        ]);
        
        return $this->render('merchant-auth',[
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionMerchantAuthPass($id){
        $idPhoto=IdPhoto::findOne($id);
        IdPhoto::updateAll(['auth_status'=>2],['user_guid'=>$idPhoto->user_guid]);
        User::updateAll(['merchant_apply_status'=>2,'merchant_role'=>1],['user_guid'=>$idPhoto->user_guid]);
        yii::$app->getSession()->setFlash('success','操作成功,卖家身份审核通过!');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionMerchantAuthDeny($id){
        $idPhoto=IdPhoto::findOne($id);
        IdPhoto::updateAll(['auth_status'=>3],['user_guid'=>$idPhoto->user_guid]);
        User::updateAll(['merchant_apply_status'=>3,'merchant_role'=>0],['user_guid'=>$idPhoto->user_guid]);
        yii::$app->getSession()->setFlash('success','操作成功,卖家身份审核通过!');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    
    public function actionVipRefundCancel($id){
        $vipRefund=VipRefund::findOne($id);
        $tans=yii::$app->db->beginTransaction();
        try{
            $vipRefund->status=2;
            if(!$vipRefund->save()) throw new Exception('update vip refund fail');
            User::updateAll(['guarantee'=>1],['user_guid'=>$vipRefund->user_guid]);
            $tans->commit();
            yii::$app->getSession()->setFlash('success','操作成功,已取消该用户的退款申请!');
            return $this->redirect(yii::$app->request->referrer);
            
        }catch (Exception $e){
            $tans->rollBack();
            yii::$app->getSession()->setFlash('error','操作失败,取消未成功!');
            return $this->redirect(yii::$app->request->referrer);
        }
    }
    
    
    
    //退还保证金
    public function actionVipRefundDo($id){
        require_once "../../common/WxpayAPI/lib/WxPay.Api.php";
        require_once '../../common/WxpayAPI/example/log.php';
        $vipRefund=VipRefund::findOne($id);
        $guaranteeFee=GuaranteeFee::findOne(['fee_guid'=>$vipRefund->fee_guid]);
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
                
                //退款成功降级为普通用户
                User::updateAll(['role_id'=>1,'guarantee'=>0],['user_guid'=>$vipRefund->user_guid]);
                $vipRefund->status=1;
                if(!$vipRefund->save()) throw new Exception();
                
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
    
    public function actionResetPassword($id){
        $model=User::findOne($id);
       
           if ($model->load(Yii::$app->request->post())) {
               $model->setPassword($model->password);
               $model->password=md5($model->password);
               if($model->save()){
                   yii::$app->getSession()->setFlash('success','密码修改成功!');
                   return $this->redirect(['view','id'=>$id]);
               }
           }
           
           unset($model->password);
           return $this->render('reset-password',[
               'model'=>$model
           ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAdmin()
    {
        $model = new AdminUser();
        $model->setScenario('create');
        if ($model->load(Yii::$app->request->post())) {
            $model->user_guid=CommonUtil::createUuid();
            $model->generateAuthKey();
            $model->setPassword($model->password);
            $model->password=md5($model->password);
            $model->password2=md5($model->password2);
            $model->role_id=97;
            $model->created_at=time();
            if($model->save())
            return $this->redirect(['view-admin', 'id' => $model->id]);
        } else {
            return $this->render('create-admin', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionUpdateAdmin($id)
    {
        $model =AdminUser::findOne($id);
        unset($model->password);
        if ($model->load(Yii::$app->request->post())) {
            $model->generateAuthKey();
            $model->setPassword($model->password);
            $model->password=md5($model->password);
            $model->password2=md5($model->password2);
            $model->updated_at=time();
            if($model->save())
                return $this->redirect(['view-admin', 'id' => $model->id]);
        } else {
            return $this->render('update-admin', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionViewAdmin($id)
    {
        $model = AdminUser::findOne($id);
    
 
            return $this->render('view-admin', [
                'model' => $model,
            ]);
        
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $openid
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            $model->updated_at=time();
            if( $model->save())
             return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $openid
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $openid
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id ])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
