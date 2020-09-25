<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Document;

/**
 * DocumentSearch represents the model behind the search form of `app\models\Document`.
 */
class DocumentSearch extends Document
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iddocument', 'iddocumentType', 'idFolder'], 'integer'],
            [['name', 'path', 'level1name', 'relativename', 'fullname', 'date', 'size', 'type'], 'safe'],
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
        $query = Document::find();

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
            'iddocument' => $this->iddocument,
            'iddocumentType' => $this->iddocumentType,
            'idFolder' => $this->idFolder,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'level1name', $this->level1name])
            ->andFilterWhere(['like', 'relativename', $this->relativename])
            ->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'size', $this->size])
            ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
