<?php

namespace backend\controllers;

use Yii;
use common\models\Coupon;
use common\models\SearchCoupon;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\CouponForm;
use common\models\RegisterCoupon;

/**
 * CouponController implements the CRUD actions for Coupon model.
 */
class CouponController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Coupon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchCoupon();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

   
    /**
     * Displays a single Coupon model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Coupon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CouponForm();

        if ($model->load(Yii::$app->request->post()) ) {
            $number=intval($_POST['number']);
            if($number<=0){
                yii::$app->getSession()->setFlash('error','创建失败,数量必须大于1');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            if($model->save()){
                yii::$app->getSession()->setFlash('success',"操作成功,新增 $number 张优惠券!");
                return $this->redirect('index');
            }
            yii::$app->getSession()->setFlash('error','创建失败,请稍后重试!');
            return $this->render('create', [
                'model' => $model,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Coupon model.
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
     * Deletes an existing Coupon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionRegisterCoupon(){
        $registerCoupon=RegisterCoupon::find()->one();
        if(empty($registerCoupon)){
            return $this->redirect('create-register-coupon');
        }
    
        return $this->redirect(['view-register-coupon','id'=>$registerCoupon->id]);
    }
    
    public function actionCreateRegisterCoupon(){
        $model=new RegisterCoupon();
        
        if($model->load(yii::$app->request->post())&&$model->save()){
            return $this->redirect(['view-register-coupon','id'=>$model->id]);
        }
        
        return $this->render('create-register-coupon',['model'=>$model]);
    }
    
    public function actionViewRegisterCoupon($id){
        $model=RegisterCoupon::findOne($id);
        
        return $this->render('view-register-coupon',['model'=>$model]);
    }
    
    public function actionUpdateRegisterCoupon($id){
        $model=RegisterCoupon::findOne($id);
        
        if($model->load(yii::$app->request->post())&&$model->save()){
            return $this->redirect(['view-register-coupon','id'=>$model->id]);
        }
        
        return $this->render('create-register-coupon',['model'=>$model]);
    }

    /**
     * Finds the Coupon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Coupon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Coupon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
