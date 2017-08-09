<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MallGoods;

/**
 * SearchMallGoods represents the model behind the search form about `common\models\MallGoods`.
 */
class SearchMallGoods extends MallGoods
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'number', 'is_sale', 'end_time', 'created_at', 'updated_at'], 'integer'],
            [['goods_guid', 'name', 'desc', 'path', 'photo'], 'safe'],
            [['price'], 'number'],
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
        $query = MallGoods::find();

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
            'price' => $this->price,
            'number' => $this->number,
            'is_sale' => $this->is_sale,
            'end_time' => $this->end_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'goods_guid', $this->goods_guid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'photo', $this->photo]);

        return $dataProvider;
    }
}
