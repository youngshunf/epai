<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\CommonUtil;
use common\models\User;
use common\models\AuthUser;
/**
 * Login form
 */
class AuthForm extends Model
{
    public $real_name;
    public $work_number;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['real_name', 'work_number'], 'required'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'real_name' => '姓名',
            'work_number'=>'工号'    
        ];
    }


    public function AuthUser()
    {
        if(!$this->validate()){
            return false;
        }
        $authUser=AuthUser::findOne(['work_number'=>$this->work_number]);
        if(empty($authUser)){
            yii::$app->getSession()->setFlash('error','绑定身份失败,请确认您输入的工号是否正确.');
            return false;
        }
        $wUser=User::findOne(['work_number'=>$this->work_number]);
        if(!empty($wUser)){
            yii::$app->getSession()->setFlash('error','此工号已被绑定,工号不能被重复绑定');
            return false;
        }
        $use_guid=$_SESSION['user']['user_guid'];
        $user=User::findOne(['user_guid'=>$use_guid]);
        $user->real_name=$this->real_name;
        $user->work_number=$this->work_number;
        $user->role_id=CommonUtil::getRoleId($authUser->role_name);
        $user->is_auth=1;
        if($user->save()){
            $_SESSION['user']=$user;
            return true;
        }
        return false;
    }
}
