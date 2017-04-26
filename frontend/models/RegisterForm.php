<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\CommonUtil;
use common\models\RegisterCoupon;
use common\models\Coupon;


/**
 * Register form
 */
class RegisterForm extends Model
{
    public $email;
    public $mobile;
    public $password;
    public $password2;
    public $agree_rules;
    private $_user = false;


    public function rules()
    {
        return [
          
            [['email', 'password','password2','mobile','agree_rules'], 'required'],    
          // 邮箱验证
            ['email', 'email'],
            ['agree_rules','compare', 'compareValue' => true,'message'=>"请同意条款"],
            //验证手机号
            ['mobile','match','pattern'=>'^[1][3-8]+\\d{9}$^','message'=>'请输入正确的手机号码'], 
            [['mobile'], 'string','max'=>11, 'min'=>11, 'tooLong'=>'手机号不能大于11位', 'tooShort'=>'手机号不能小于11位'],           
            ['mobile', 'unique','targetClass' => '\common\models\User', 'message'=>'该手机已注册'],            
           //验证邮箱是否注册
            ['email', 'unique','targetClass' => '\common\models\User', 'message'=>'邮箱已存在'], 
            // 验证两次输入的密码是否一致
            ['password2', 'compare', 'compareAttribute'=>'password','message'=>'两次密码不一致'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'password2' => '确认密码',
            'password' => '密码',       
            'mobile' => '手机号',         
            'email' => '邮箱',       
        ];
    }

    /**
     * 注册
     *
     */
    public function register()
    {
        	$user= new User();      
        	$user->mobile=$this->mobile;
        	$user->user_guid=CommonUtil::createUuid();
        	$user->email=$this->email;
        	$user->generateAuthKey();
        	$user->setPassword($this->password);
        	$user->generateAuthKey();
        	$user->created_at=time();
        	$user->password=md5($this->password);
           	if($user->save()){
           	    //发放注册优惠
           	    $registerCoupon=RegisterCoupon::find()->andWhere(['is_open'=>1])->one();
           	    if(!empty($registerCoupon)){
           	        $coupon=new Coupon();
           	        $coupon->coupon_code=Coupon::generateCouponCode();
           	        $coupon->user_guid=$user->user_guid;
           	        $coupon->amount=$registerCoupon->amount;
           	        $coupon->min_amount=$registerCoupon->min_amount;
           	        $coupon->end_time=strtotime("+".$registerCoupon->expire_day." day");
           	        $coupon->type=1;
           	        $coupon->remark=$registerCoupon->remark;
           	        $coupon->status=1;
           	        $coupon->get_time=time();
           	        $coupon->created_at=time();
           	        $coupon->save();
           	    }
           	    return true;
           	}
           	
           	return false;
        				
    }

}
