<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\web\HttpException;
use frontend\models\LoginUser;

/**
 * Register form
 */
class WishForm extends Model
{
    public $nick;
    public $post;
    public $birthday;
    public $sex;
    public $province;
    public $city;
    public $region;
    public $wish_title;
    public $wish_from;
    public $meaning;
    public $mobile;
    public $weixin;
    public $qq;
    public $email;
    public $amount;
    public $return;
    public $type;


    public function rules()
    {
        return [          
            [['nick', 'post','birthday','sex','province','city','region','wish_title','wish_from','meaning','mobile','weixin','qq','email','amount','return','type'], 'required'],
   
          // 邮箱验证
            ['email', 'email'],
      //验证手机号
          ['mobile','match','pattern'=>'^[1][3-8]+\\d{9}$^','message'=>'请输入正确的手机号码'], 
            [['mobile'], 'string','max'=>11, 'min'=>11, 'tooLong'=>'手机号不能大于11位', 'tooShort'=>'手机号不能小于11位'],     
            [['amount','qq'], 'number'],
        //真实姓名
      ];
    }
    public function attributeLabels()
    {
        return [
           'nick'=>'昵称',
            'post'=>'职业',
            'birthday'=>'生日',
            'sex'=>'性别',
            'province'=>'省份',
            'city'=>'城市',
           'region'=> '生活区域',
            'wish_title' => '愿望',
            'wish_from' => '愿望由来',
            'meaning' => '对你的意义',
            'mobile' => '手机号',
            'weixin' => '微信',
            'qq' => 'QQ号',
            'email' => '邮箱',
            'amount' => '目标金额',
            'return' => '实现愿望后的回报',      
            'type' => '愿望类型',
        
        ];
    }

    public function AddWish()
    {

    } 

    

  
}
