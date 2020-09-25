<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Client;

/**
 * ClientSearch represents the model behind the search form of `app\models\Client`.
 */
class ClientSearch extends Client
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idClient'], 'integer'],
            [['entity', 'name', 'state_id', 'state_code', 'state', 'town', 'email', 'phone', 'idprof1', 'code_client', 'ref', 'country_id', 'country_code', 'country'], 'safe'],
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
        $query = Client::find();

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
            'idClient' => $this->idClient,
        ]);

        $query->andFilterWhere(['like', 'entity', $this->entity])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'state_id', $this->state_id])
            ->andFilterWhere(['like', 'state_code', $this->state_code])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'town', $this->town])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'idprof1', $this->idprof1])
            ->andFilterWhere(['like', 'code_client', $this->code_client])
            ->andFilterWhere(['like', 'ref', $this->ref])
            ->andFilterWhere(['like', 'country_id', $this->country_id])
            ->andFilterWhere(['like', 'country_code', $this->country_code])
            ->andFilterWhere(['like', 'country', $this->country]);

        return $dataProvider;
    }
}
