<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AuctionGoods;

/**
 * SearchAuctionGoods represents the model behind the search form about `common\models\AuctionGoods`.
 */
class SearchAuctionGoods extends AuctionGoods
{
 
    public $queryType=0;//前台0,后台1
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cateid','roundid', 'count_auction', 'count_view', 'count_collection', 'start_time', 'end_time', 'created_at','status', 'updated_at','post_type','auth_status'], 'integer'],
            [['goods_guid', 'name', 'desc', 'deal_user','user_guid'], 'safe'],
            [['start_price', 'delta_price', 'lowest_deal_price', 'current_price', 'deal_price'], 'number'],
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
        $query = AuctionGoods::find()->orderBy('sort desc,created_at asc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $now=time();
        if($this->status=='1'){
            $query->andWhere(" start_time <= $now and end_time >= $now");
        }
       if($this->status=='2'){
            $query->andWhere(" start_time >= $now ");
        }
        if($this->status=='3'){
            $query->andWhere(" end_time <= $now ");
        }
        
        if($this->queryType==1){
            $query->andWhere(" auth_status !=-1");
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'auth_status'=>$this->auth_status,
            'post_type'=>$this->post_type,
            'user_guid'=>$this->user_guid,
            'status'=>$this->status,
            'cateid' => $this->cateid,
            'roundid'=>$this->roundid,
            'start_price' => $this->start_price,
            'delta_price' => $this->delta_price,
            'lowest_deal_price' => $this->lowest_deal_price,
            'current_price' => $this->current_price,
            'count_auction' => $this->count_auction,
            'count_view' => $this->count_view,
            'count_collection' => $this->count_collection,
            'deal_price' => $this->deal_price,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'goods_guid', $this->goods_guid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'deal_user', $this->deal_user]);

        return $dataProvider;
    }
}
