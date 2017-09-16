<?php

namespace wechat\controllers;

use Yii;
use common\models\MallGoods;
use common\models\SearchMallGoods;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\CommonUtil;
use common\models\ImageUploader;
use common\models\Order;
use yii\filters\AccessControl;
use common\models\Address;

/**
 * MallController implements the CRUD actions for MallGoods model.
 */
class MallController extends Controller
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
        yii::$app->getUser()->setReturnUrl(yii::$app->getRequest()->getAbsoluteUrl());
        
     if(!yii::$app->user->isGuest){
            if(!empty(yii::$app->user->identity->openid)&&yii::$app->user->identity->is_band==0){
                  return $this->redirect(['site/band-user']);
            }
        }
        return parent::beforeAction($action);
    }
    /**
     * Lists all MallGoods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchMallGoods();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MallGoods model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model= $this->findModel($id);
//         $couponCount=Coupon::find()->andWhere(['user_guid'=>yii::$app->user->identity->user_guid])
        return $this->render('view', [
            'model' =>$model,
        ]);
    }
    
    public function actionSubmitOrder(){
        $goods_guid=$_POST['goods-guid'];
        $number=$_POST['number'];
        $user_guid=yii::$app->user->identity->user_guid;
        $goods=MallGoods::findOne(['goods_guid'=>$goods_guid]);
        
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
        $order->orderno=Order::getOrderNO(Order::TYPE_MALL);
        $order->type=Order::TYPE_MALL;
        $order->amount=$number*$goods->price;
        $order->number=$number;
        $order->goods_name=$goods->name;
        $order->address_id=$address->id;
        $order->address=$address['province'].' '.$address['city'].' '.$address['district'].' '.$address['address'].' '.$address['company'].' '.$address['name'].' '.$address['phone'];
        $order->created_at=time();
        if(!$order->save()){
            yii::$app->getSession()->setFlash('error','提交订单失败!');
            return $this->redirect(yii::$app->getRequest()->referrer);
        }
        yii::$app->getUser()->setReturnUrl(yii::$app->getRequest()->referrer);
        return $this->redirect(['site/pay-order','order_guid'=>$order->order_guid]);
    }

    /**
     * Creates a new MallGoods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MallGoods();

        if ($model->load(Yii::$app->request->post()) ) {
            $model->user_guid=yii::$app->user->identity->user_guid;
            $model->goods_guid=CommonUtil::createUuid();
            $model->desc=@$_POST['goods-desc'];
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->end_time=strtotime($model->end_time);
            $model->created_at=time();
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MallGoods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            $model->user_guid=yii::$app->user->identity->user_guid;
            $model->goods_guid=CommonUtil::createUuid();
            $model->desc=@$_POST['goods-desc'];
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->end_time=strtotime($model->end_time);
            $model->updated_at=time();
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MallGoods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MallGoods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MallGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MallGoods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
