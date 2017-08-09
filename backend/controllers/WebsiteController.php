<?php

namespace backend\Controllers;

use Yii;
use common\models\HomePhoto;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ImageUploader;
use common\models\Siteinfo;
use yii\filters\AccessControl;

/**
 * WebsiteController implements the CRUD actions for HomePhoto model.
 */
class WebsiteController extends Controller
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

    /**
     * Lists all HomePhoto models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => HomePhoto::find(),
        ]);
    
        $this->layout="@backend/views/layouts/website_layout.php";
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HomePhoto model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewPhoto($id)
    {
        $this->layout="@backend/views/layouts/website_layout.php";
        return $this->render('view-photo', [
            'model' => $this->findModel($id),
        ]);
    }
    
    public function actionViewSiteinfo($id)
    {
        $model=Siteinfo::findOne($id);
        $this->layout="@backend/views/layouts/website_layout.php";
        return $this->render('view-siteinfo', [
            'model' =>$model,
        ]);
    }

    /**
     * Creates a new HomePhoto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreatePhoto()
    {
        $model = new HomePhoto();

        if ($model->load(Yii::$app->request->post()) ) {
            
            $photo=ImageUploader::uploadHomePhoto('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->desc=@$_POST['desc'];
            $model->created_at=time();
            if($model->save())
            return $this->redirect(['view-photo', 'id' => $model->id]);
        } else {
            $this->layout="@backend/views/layouts/website_layout.php";
            return $this->render('create-photo', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionCreateSiteinfo()
    {
        $model = new Siteinfo();
    
        if ($model->load(Yii::$app->request->post()) ) {
            $model->content=@$_POST['info-content'];
            $model->created_at=time();
            if($model->save())
                return $this->redirect(['view-siteinfo', 'id' => $model->id]);
        } else {
            $this->layout="@backend/views/layouts/website_layout.php";
            return $this->render('create-siteinfo', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionUpdateSiteinfo($id)
    {
        $model = Siteinfo::findOne($id);
 
          if ($model->load(Yii::$app->request->post()) ) {
                $model->content=@$_POST['info-content'];
                $model->updated_at=time();
                if($model->save())
                    return $this->redirect(['view-siteinfo', 'id' => $model->id]);
            } else {
                $this->layout="@backend/views/layouts/website_layout.php";
                return $this->render('update-siteinfo', [
                    'model' => $model,
                ]);
            }
        
    }

    /**
     * Updates an existing HomePhoto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdatePhoto($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            $photo=ImageUploader::uploadHomePhoto('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->desc=@$_POST['desc'];
            $model->updated_at=time();
            if($model->save())
            return $this->redirect(['view-photo', 'id' => $model->id]);
        } else {
            $this->layout="@backend/views/layouts/website_layout.php";
            return $this->render('update-photo', [
                'model' => $model,
            ]);
        }
    }

    public function actionCollect($id){
        $model=Siteinfo::findOne($id);
        $this->layout="@backend/views/layouts/website_layout.php";
        return $this->render('view-siteinfo',['model'=>$model]);
    }
    public function actionMortage($id){
        $model=Siteinfo::findOne($id);
        $this->layout="@backend/views/layouts/website_layout.php";
        return $this->render('view-siteinfo',['model'=>$model]);
    }
    public function actionContactUs($id){
       $model=Siteinfo::findOne($id);
       $this->layout="@backend/views/layouts/website_layout.php";
        return $this->render('view-siteinfo',['model'=>$model]);
    }
    public function actionAuctionRules($id){
        $model=Siteinfo::findOne($id);
        $this->layout="@backend/views/layouts/website_layout.php";
        return $this->render('view-siteinfo',['model'=>$model]);
    }
   
    public function actionDeletePhoto($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the HomePhoto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HomePhoto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HomePhoto::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
