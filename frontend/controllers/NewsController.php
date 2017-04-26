<?php

namespace frontend\Controllers;

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
    
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 新闻资讯
     * @return mixed
     */
    public function actionIndex()
    {
      $model=News::find()->andWhere(['is_recommend'=>1,'cateid'=>1])->orderBy('created_at desc')->limit(8)->all();
        
        $dataProvider = new ActiveDataProvider([
            'query'=>News::find()->andWhere(['cateid'=>1])->orderBy('created_at desc'),
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);
        $this->layout="@frontend/views/layouts/news_layout.php";
        return $this->render('index', [
             'model'=>$model,
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
        $model=$this->findModel($id);
        $model->count_view+=1;
        $model->save();       
        $title=$model->title;
        $relativeNews=News::find()->andWhere(['cateid'=>1])->limit(10)->orderBy('created_at desc ')->all();
        $this->layout="@frontend/views/layouts/news_layout.php";
        return $this->render('view', [
            'model' => $model,
            'relativeNews'=>$relativeNews,      
        ]);
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
        
    
    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
        
    
    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
        
    
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
