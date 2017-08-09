<?php

namespace backend\Controllers;

use Yii;
use common\models\MallGoods;
use common\models\SearchMallGoods;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\CommonUtil;
use common\models\ImageUploader;
use yii\filters\AccessControl;

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
        ];
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
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
