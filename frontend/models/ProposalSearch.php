<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Proposal;

/**
 * ProposalSearch represents the model behind the search form of `app\models\Proposal`.
 */
class ProposalSearch extends Proposal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idProposal', 'id', 'idFolder'], 'integer'],
            [['entity', 'socid', 'ref'], 'safe'],
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
        $query = Proposal::find();

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
            'idProposal' => $this->idProposal,
            'id' => $this->id,
            'idFolder' => $this->idFolder,
        ]);

        $query->andFilterWhere(['like', 'entity', $this->entity])
            ->andFilterWhere(['like', 'socid', $this->socid])
            ->andFilterWhere(['like', 'ref', $this->ref]);

        return $dataProvider;
    }
}
