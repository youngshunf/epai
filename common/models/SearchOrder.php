<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * SearchOrder represents the model behind the search form about `common\models\Order`.
 */
class SearchOrder extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'number', 'status', 'is_pay', 'pay_time', 'sent_time', 'confirm_time', 'cancel_time', 'created_at', 'updated_at'], 'integer'],
            [['user_guid', 'order_guid', 'orderno', 'biz_guid', 'goods_name', 'express_number','merchant_user','order_type'], 'safe'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Order::find()->orderBy('created_at desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'merchant_user'=>$this->merchant_user,
            'type' => $this->type,
            'number' => $this->number,
            'amount' => $this->amount,
            'status' => $this->status,
            'is_pay' => $this->is_pay,
            'pay_time' => $this->pay_time,
            'sent_time' => $this->sent_time,
            'confirm_time' => $this->confirm_time,
            'cancel_time' => $this->cancel_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'user_guid', $this->user_guid])
            ->andFilterWhere(['like', 'order_guid', $this->order_guid])
            ->andFilterWhere(['like', 'orderno', $this->orderno])
            ->andFilterWhere(['like', 'biz_guid', $this->biz_guid])
            ->andFilterWhere(['like', 'goods_name', $this->goods_name])
            ->andFilterWhere(['like', 'express_number', $this->express_number]);

        return $dataProvider;
    }
}
