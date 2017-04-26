<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $user_guid
 * @property string $province
 * @property string $city
 * @property string $address
 * @property string $postcode
 * @property string $name
 * @property string $phone
 * @property string $company
 * @property integer $is_default
 * @property string $created_at
 * @property string $updated_at
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_default', 'created_at', 'updated_at'], 'integer'],
            [['user_guid', 'company'], 'string', 'max' => 48],
            [['province', 'city', 'postcode', 'name', 'phone'], 'string', 'max' => 32],
            [['address'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_guid' => 'User Guid',
            'province' => '省份',
            'city' => '城市',
            'address' => '地址',
            'postcode' => '邮编',
            'name' => '收件人姓名',
            'phone' => '手机号',
            'company' => '收件单位',
            'is_default' => '是否默认收货地址',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
