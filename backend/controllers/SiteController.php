<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\LoginForm;
use common\models\RegisterForm;
use yii\filters\VerbFilter;
use common\models\MonthFeeCalEventHandler;
use common\models\MonthCloseEventHandler;
use common\models\MonthFee;
use common\models\CommonUtil;
use common\models\UserRelation;
use common\models\MembersOrder;
use common\models\Order;
use common\models\AwardCommonUtil;
use common\models\AwardPoints;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','register'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
     
  
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionRegister(){
    	$model = new RegisterForm();
    	if ($model->load(Yii::$app->request->post()) && $model->register()) {
    		return $this->goBack();
    	} else {
    		return $this->render('register', [
    				'model' => $model,
    				]);
    	}
    }

    public function actionLogout()
    {
        Yii::$app->user->logout(false);

        return $this->goHome();
    }
}
