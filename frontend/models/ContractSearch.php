<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Contract;

/**
 * ContractSearch represents the model behind the search form of `app\models\Contract`.
 */
class ContractSearch extends Contract
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idContract', 'idFolder'], 'integer'],
            [['entityID', 'socID', 'ref', 'fkSoc'], 'safe'],
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
        $query = Contract::find();

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
            'idContract' => $this->idContract,
            'idFolder' => $this->idFolder,
        ]);

        $query->andFilterWhere(['like', 'entityID', $this->entityID])
            ->andFilterWhere(['like', 'socID', $this->socID])
            ->andFilterWhere(['like', 'ref', $this->ref])
            ->andFilterWhere(['like', 'fkSoc', $this->fkSoc]);

        return $dataProvider;
    }
}
