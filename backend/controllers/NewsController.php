<?php

namespace backend\Controllers;

use Yii;
use common\models\News;
use common\models\SearchNews;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\ImageUploader;
use yii\data\ActiveDataProvider;
use common\models\NewsCate;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
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
       /*      'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ], */
        ];
    }

    /**
     * 新闻资讯
     * @return mixed
     */
    public function actionIndex()
    {
     return $this->redirect(['news','cateid'=>1]);
        $dataProvider = new ActiveDataProvider([
            'query'=>NewsCate::find(),
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);
        $this->layout="@backend/views/layouts/news_layout.php";
        return $this->render('index', [
         
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * 新闻资讯
     * @return mixed
     */
    public function actionNews($cateid)
    {
       
        $searchModel = new SearchNews();
        $searchModel->cateid=$cateid;
        $cate=NewsCate::findOne(['cateid'=>$cateid]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       $this->layout="@backend/views/layouts/news_layout.php";
        return $this->render('news', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'cate'=>$cate
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->layout="@backend/views/layouts/news_layout.php";
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    public function actionRecommend($id)
    {
       $model=$this->findModel($id);
     
        if($model->is_recommend==0){
            yii::$app->getSession()->setFlash('success',$model->title."---推荐成功!");
            $model->is_recommend=1;
        }else{
            yii::$app->getSession()->setFlash('success',$model->title."---已取消推荐!");
            $model->is_recommend=0;
        }
        if($model->save()){
                    
            return $this->redirect(['news', 
                'cateid' =>$model->cateid ,
            ]);
        }
    }
    
    public function actionViewCate($id)
    {
        $model=NewsCate::findOne($id);
        $this->layout="@backend/views/layouts/news_layout.php";
        return $this->render('view-cate', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new News();
      
        $model->cateid=$id;
        $cate=NewsCate::find()->all();
        if ($model->load(Yii::$app->request->post()) ) {
            $model->user_guid=yii::$app->user->identity->user_guid;
            $model->content=@$_POST['news-content'];
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->created_at=time();
             if($model->save())
                     return $this->redirect(['view', 'id' => $model->newsid]);
        } else {
            $this->layout="@backend/views/layouts/news_layout.php";
            return $this->render('create', [
                'model' => $model,
                'cate'=>$cate
            ]);
        }
    }
    
    public function actionCreateCate()
    {
        $model = new NewsCate();
    
        if ($model->load(Yii::$app->request->post()) ) {
            $model->user_guid=yii::$app->user->identity->user_guid;         
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->created_at=time();
            if($model->save())
                return $this->redirect(['view-cate', 'id' => $model->cateid]);
        } else {
            $this->layout="@backend/views/layouts/news_layout.php";
            return $this->render('create-cate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $cate=NewsCate::find()->all();
        if ($model->load(Yii::$app->request->post()) ) {
         
            $model->user_guid=yii::$app->user->identity->user_guid;
            $model->content=@$_POST['news-content'];
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->updated_at=time();
            if($model->save())
                 return $this->redirect(['view', 'id' => $model->newsid]);
        } else {
            $this->layout="@backend/views/layouts/news_layout.php";
            return $this->render('update', [
                'model' => $model,
                'cate'=>$cate
            ]);
        }
    }
    
    public function actionUpdateCate($id)
    {
        $model = NewsCate::findOne($id);
    
        if ($model->load(Yii::$app->request->post()) ) {
            $model->user_guid=yii::$app->user->identity->user_guid;
    
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            $model->created_at=time();
            if($model->save())
                return $this->redirect(['view-cate', 'id' => $model->cateid]);
        } else {
            $this->layout="@backend/views/layouts/news_layout.php";
            return $this->render('update-cate', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionDeleteCate($id)
    {
       
    
        return $this->redirect(['index']);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
