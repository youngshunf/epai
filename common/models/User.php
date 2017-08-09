<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $openid
 * @property string $username
 * @property string $access_token
 * @property string $auth_key
 * @property string $password
 * @property string $password_hash
 * @property integer $role_id
 * @property string $real_name
 * @property string $nick
 * @property string $post
 * @property integer $age
 * @property string $birthday
 * @property integer $sex
 * @property string $province
 * @property string $city
 * @property string $country
 * @property string $address
 * @property string $path
 * @property string $photo
 * @property string $img_path
 * @property string $mobile
 * @property integer $mobile_auth
 * @property string $email
 * @property integer $email_auth
 * @property string $district
 * @property string $sign
 * @property string $subscribe_time
 * @property string $created_at
 * @property string $updated_at
 * @property string $user_guid
 * @property string $region
 * @property string $ideal
 * @property string $motto
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [    
            // 邮箱验证
            [['role_id','sex'],'integer'],
            ['email', 'email'],
            [['name','nick','province','city','mobile','country','address','district','password','company_address'],'string'],
            [['name','id_code','home_address','province','city','country','email'],'required','on'=>['merchant-register']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'openID',
            'username' => '用户名',
            'access_token' => 'Access Token',
            'auth_key' => 'Auth Key',
            'password' => 'Password',
            'password_hash' => 'Password Hash',
            'merchant_type'=>'卖家类型',
            'merchant_role'=>'卖家角色',
            'id_code'=>'身份证号',
            'home_address'=>'家庭地址',
            'company_address'=>'公司地址',
            'role_id' => '角色',
            'name' => '姓名',
            'nick' => '昵称',
            'post' => '职业',
            'age' => '年龄',
            'birthday' => '生日',
            'sex' => '性别',
            'province' => '省份',
            'city' => '城市',
            'country' => '国家',
            'weixin'=>'微信号',
            'qq'=>'QQ号',
            'address' => '地址',
            'path' => 'Path',
            'photo' => 'Photo',
            'img_path' => '头像',
            'mobile' => '手机号',
            'mobile_auth' => '手机验证',
            'email' => '邮箱',
            'email_auth' => '邮箱验证',
            'district' => '地区',
            'sign' => '个人签名',
            'subscribe_time' => '关注时间',
            'created_at' => '注册时间',
            'updated_at' => '更新时间',
            'last_ip'=>'最后登录IP',
            'last_time'=>'最后登录时间'
  
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username,$password)
    {
        return static::find()->where("password='$password' AND(username='$username' OR email='$username' OR mobile='$username')")
        ->one();
        // return static::findOne(['username' => $username,'password'=>md5($password)]);
    }
    
    public static function findByOpenid($openid){
        return static::find()->andWhere(['openid'=>$openid])->one();
    }
    
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
    
        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }
    
    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }
    
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    
    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    
    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
