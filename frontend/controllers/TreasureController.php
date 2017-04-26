<?php

namespace frontend\Controllers;

use Yii;
use common\models\News;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;


class TreasureController extends Controller
{
/*     public function behaviors()
    {
        return [
    
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    } */

    /**
     * 知文玩
     * @return mixed
     */
    public function actionIndex()
    {
      $model=News::find()->andWhere(['is_recommend'=>1,'cateid'=>2])->orderBy('created_at desc')->limit(8)->all();
        
        $dataProvider = new ActiveDataProvider([
            'query'=>News::find()->andWhere(['cateid'=>2])->orderBy('created_at desc'),
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

    public function actionView($id)
    {
        $model=$this->findModel($id);
        $model->count_view+=1;
        $model->save();       
         $relativeNews=News::find()->andWhere(['cateid'=>1])->limit(10)->orderBy('created_at desc ')->all();
        $this->layout="@frontend/views/layouts/news_layout.php";
        return $this->render('view', [
            'model' => $model,
            'relativeNews'=>$relativeNews,      
        ]);
    }
    
       
  
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
