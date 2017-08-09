<?php
namespace wechat\models;

use Yii;
use yii\base\Model;

use common\models\CommonUtil;
use common\models\User;

/**
 * Login form
 */
class BandForm extends Model
{
    public $username;
    public $password;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
           
        ];
    }

}
