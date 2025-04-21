<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LookupType;

/**
 * LookupTypeSearch represents the model behind the search form of `app\models\LookupType`.
 */
class LookupTypeSearch extends LookupType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lookup_type_id'], 'integer'],
            [['name', 'sort_direction'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = LookupType::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'lookup_type_id' => $this->lookup_type_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sort_direction', $this->sort_direction]);

        return $dataProvider;
    }
}
