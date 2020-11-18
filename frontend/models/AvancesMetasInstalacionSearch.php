<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AvancesMetasInstalacion;

/**
 * AvancesMetasInstalacionSearch represents the model behind the search form of `app\models\AvancesMetasInstalacion`.
 */
class AvancesMetasInstalacionSearch extends AvancesMetasInstalacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['avances_metas_instalacion_id'], 'integer'],
            [['DANE', 'Departamento', 'Municipio', 'Meta', 'Beneficiarios_Instalados', 'Avance'], 'safe'],
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
        $query = AvancesMetasInstalacion::find();

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
            'avances_metas_instalacion_id' => $this->avances_metas_instalacion_id,
        ]);

        $query->andFilterWhere(['like', 'DANE', $this->DANE])
            ->andFilterWhere(['like', 'Departamento', $this->Departamento])
            ->andFilterWhere(['like', 'Municipio', $this->Municipio])
            ->andFilterWhere(['like', 'Meta', $this->Meta])
            ->andFilterWhere(['like', 'Beneficiarios_Instalados', $this->Beneficiarios_Instalados])
            ->andFilterWhere(['like', 'Avance', $this->Avance]);

        return $dataProvider;
    }
}
