<?php
namespace frontend\models;


use yii;
use yii\base\Model;
use common\models\User;
use common\models\CommonUtil;
use common\models\WeChat;
/**
 * 服务号登录
 */
class WeChatLogin extends Model
{
    
    public function Login($code){
        
        $res=WeChat::getAccessTokenAndOpenid($code);
   
        $res=json_decode($res,true);
        if(!empty($res['errcode'])){
            return false;
        }
        
        $access_token=$res['access_token'];
    
        $openid=$res['openid'];
        
        $model=User::findOne(['openid'=>$openid]);
        if(!empty($model)){
            return Yii::$app->user->login($model,3600 * 24);         
        }
        
        $model=$this->Register($access_token,$openid);
        if($model){
              return Yii::$app->user->login($model,3600 * 24);       
        }   
             
        return false;
    }
    
    public function Register($access_token,$openid){
          
        $userInfo=WeChat::getUserReturn($access_token, $openid);  
     
        $userInfo=json_decode($userInfo,true);        
        if(!empty($userInfo['errcode'])){
        return false;
        } 
         
        $model=new User();
        $model->user_guid=CommonUtil::createUuid();
        $model->openid=$openid;
        //处理昵称表情符号
             $nick=$userInfo['nickname'];
         $nick = preg_replace_callback('/[\xf0-\xf7].{3}/', function($r) { return "";}, $nick);
         $model->nick=$nick;
         $model->sex=$userInfo['sex'];
         $model->city=$userInfo['city'];
         $model->province=$userInfo['province'];
         $model->country=$userInfo['country'];
         $model->img_path=$userInfo['headimgurl'];
        // $model->subscribe_time=$userInfo['subscribe_time']; 
         $model->created_at=time();
        if($model->save()){
           return $model;
        }
        
        return false;
    }
    
   

}
