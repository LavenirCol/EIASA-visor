<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Hsstock;

/**
 * HsstockSearch represents the model behind the search form of `app\models\Hsstock`.
 */
class HsstockSearch extends Hsstock
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'pid', 'uuid', 'name', 'factory', 'model', 'datecreate', 'sku', 'health_reg', 'quantity', 'measure', 'location', 'city', 'city_code', 'district', 'district_code', 'lat', 'lng'], 'safe'],
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
        $query = Hsstock::find();

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
        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'pid', $this->pid])
            ->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'factory', $this->factory])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'datecreate', $this->datecreate])
            ->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'health_reg', $this->health_reg])
            ->andFilterWhere(['like', 'quantity', $this->quantity])
            ->andFilterWhere(['like', 'measure', $this->measure])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'city_code', $this->city_code])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'district_code', $this->district_code])
            ->andFilterWhere(['like', 'lat', $this->lat])
            ->andFilterWhere(['like', 'lng', $this->lng]);

        return $dataProvider;
    }
}
