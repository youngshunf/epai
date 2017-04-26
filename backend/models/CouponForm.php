<?php
namespace backend\models;
use yii;
use yii\base\Model;
use common\models\Coupon;
/**
 * Register form
 */
class CouponForm extends Model
{
    public $amount;
    public $min_amount;
    public $end_time;
    public $remark;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [          
            [['amount', 'min_amount','end_time'], 'required'],
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
        ];
    }

    /**
     * 添加产品
     *
     */
    public function save()
    {
    	$user_guid=yii::$app->user->identity->user_guid;
    	$number=intval($_POST['number']);
    	if($number>0){
    	    for ($i=0;$i<$number;$i++){
    	        $coupon=new Coupon();
    	        $coupon->created_at=$user_guid;
    	        $coupon->coupon_code=Coupon::generateCouponCode();
    	        $coupon->amount=$this->amount;
    	        $coupon->min_amount=$this->min_amount;
    	        $coupon->end_time=strtotime($this->end_time);
    	        $coupon->remark=$this->remark;
    	        $coupon->type=2;
    	        $coupon->created_at=time();
    	        if(!$coupon->save()){
    	            return false;
    	        }
    	    }
    	    return true;
    	}
    	
    	return false;
        
    }
    
}
