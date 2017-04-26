<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\News;

/**
 * SearchNews represents the model behind the search form about `common\models\News`.
 */
class SearchNews extends News
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newsid', 'cateid', 'created_at', 'updated_at'], 'integer'],
            [['user_guid', 'title', 'content', 'path', 'photo'], 'safe'],
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
        $query = News::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy('created_at desc'),
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'newsid' => $this->newsid,
            'cateid' => $this->cateid,
         
        ]);

        $query->andFilterWhere(['like', 'user_guid', $this->user_guid])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'photo', $this->photo]);

        return $dataProvider;
    }
}
