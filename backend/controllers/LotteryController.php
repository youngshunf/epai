<?php

namespace backend\controllers;

use Yii;
use common\models\LotteryGoods;
use common\models\SearchLotteryGoods;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\CommonUtil;
use common\models\ImageUploader;
use yii\data\ActiveDataProvider;
use common\models\LotteryRec;
use common\models\User;
use yii\db\Expression;
use yii\db\Exception;
use yii\filters\AccessControl;

/**
 * LotteryController implements the CRUD actions for LotteryGoods model.
 */
class LotteryController extends Controller
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
     * Lists all LotteryGoods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchLotteryGoods();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        $dataProvider=new ActiveDataProvider([
            'query'=>LotteryRec::find()->andWhere(['goods_guid'=>$model->goods_guid])->orderBy('created_at desc'),
        ]);
        return $this->render('view', [
            'model' =>$model ,
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionManualLottery(){
        $goods_guid=$_POST['goods_guid'];
        $mobile=$_POST['mobile'];
        $user=User::findOne(['mobile'=>$mobile]);
        if(empty($user)){
            yii::$app->getSession()->setFlash('error','用户不存在');
            return $this->redirect(yii::$app->request->referrer);
        }
        $lotteryRec=LotteryRec::findOne(['user_guid'=>$user->user_guid,'goods_guid'=>$goods_guid]);
        if(empty($lotteryRec)){
            yii::$app->getSession()->setFlash('error','该用户没有参与夺宝!');
            return $this->redirect(yii::$app->request->referrer);
        }
        
        LotteryRec::updateAll(['is_award'=>0],['goods_guid'=>$goods_guid]);
        
        $lotteryRec->is_award=1;
        if($lotteryRec->save()){
            yii::$app->getSession()->setFlash('success','设置成功!');
            return $this->redirect(yii::$app->request->referrer);
        }
        yii::$app->getSession()->setFlash('error','设置失败！!');
        return $this->redirect(yii::$app->request->referrer);
        
    }
    
    /**
     * 对参与人次进行作弊
     */
    public function actionManualLotteryRec(){
        $goods_guid=$_POST['goods_guid'];
        $numbers=$_POST['numbers'];
        $goods=LotteryGoods::findOne(['goods_guid'=>$goods_guid]);
        if($numbers>=$goods->price-$goods->count_lottery){
             yii::$app->getSession()->setFlash('error','参与人次不能大于剩余人次!');
             return $this->redirect(yii::$app->request->referrer);
        }
        $trans=yii::$app->db->beginTransaction();
        
        try{
        //新建虚拟用户
        $virtualUser=new User();
        $virtualUser->user_guid=CommonUtil::createUuid();
        $virtualUser->mobile=CommonUtil::getRandomMobile();
        $virtualUser->role_id=0;
        $virtualUser->password=md5('123456');
        $virtualUser->img_path=$virtualUser->img_path=yii::$app->params['virtualAvatarUrl'].rand(1, 20).'.png';
        $virtualUser->created_at=time();
        if(!$virtualUser->save()) throw new Exception();
        
        for( $i=0;$i<$numbers;$i++){
            $lotteryRec=new LotteryRec();
            $lotteryRec->goods_guid=$goods->goods_guid;
            $lotteryRec->user_guid=$virtualUser->user_guid;
            $lotteryRec->lottery_code=LotteryRec::getLotteryCode();
            $lotteryRec->is_virtual=1;
            $lotteryRec->created_at=time();
            if(!$lotteryRec->save() ) throw new Exception();
        }
        
        $goods->count_lottery+=$numbers;
        $goods->updated_at=time();
        if(!$goods->save()) throw new Exception();
        
        $trans->commit();
        yii::$app->getSession()->setFlash('success','操作成功!共增加'.$numbers.'参与人次!');
        }catch (Exception $e){
            $trans->rollBack();
            yii::$app->getSession()->setFlash('error','增加参与人次失败!');
        }
        
            return $this->redirect(yii::$app->request->referrer);
    }
    
    /**
     * Creates a new LotteryGoods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LotteryGoods();

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
     * Updates an existing LotteryGoods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->end_time=date("Y-m-d H:i",$model->end_time);
        if ($model->load(Yii::$app->request->post()) ) {
            $model->desc=@$_POST['goods-desc'];
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->end_time=strtotime($model->end_time);
            if($model->status!=0 && $model->end_time>time()){
                if($model->price>$model->count_lottery){
                    $model->status=0;
                }
            }
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
     * Deletes an existing LotteryGoods model.
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
     * Finds the LotteryGoods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LotteryGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LotteryGoods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
