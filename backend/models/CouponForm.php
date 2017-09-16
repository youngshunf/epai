<?php
namespace backend\models;
use yii;
use yii\base\Model;
use common\models\Coupon;
use common\models\User;
/**
 * Register form
 */
class CouponForm extends Model
{
    public $amount;
    public $min_amount;
    public $end_time;
    public $remark;
    public $mobile;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [          
            [['amount', 'min_amount','end_time','mobile'], 'required'],
            [['amount', 'min_amount'], 'number'],
            [['remark'], 'string'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'amount' => '优惠券金额(元)',
            'end_time' => '过期时间',
            'min_amount' => '使用门槛(元)',
            'remark' => '备注',
            'mobile'=>'手机号'
        ];
    }

    /**
     * 添加产品
     *
     */
    public function save()
    {
    	$user_guid=yii::$app->user->identity->user_guid;
    	$user=User::findOne(['mobile'=>$this->mobile]);
    	if(empty($user)){
    	    yii::$app->getSession()->setFlash('error','用户不存在!');
    	    return false;
    	}
    	        $coupon=new Coupon();
    	        $coupon->created_user=$user_guid;
    	        $coupon->coupon_code=Coupon::generateCouponCode();
    	        $coupon->user_guid=$user->user_guid;
    	        $coupon->mobile=$this->mobile;
    	        $coupon->amount=$this->amount;
    	        $coupon->status=1;
    	        $coupon->min_amount=$this->min_amount;
    	        $coupon->end_time=strtotime($this->end_time);
    	        $coupon->remark=$this->remark;
    	        $coupon->type=2;
    	        $coupon->created_at=time();
    	        if(!$coupon->save()){
    	            yii::$app->getSession()->setFlash('error','发放失败!');
    	            return false;
    	        }
    	    
    	    return true;
    }
    
}
