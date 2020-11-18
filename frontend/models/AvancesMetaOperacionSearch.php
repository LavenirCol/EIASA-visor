<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AvancesMetaOperacion;

/**
 * AvancesMetaOperacionSearch represents the model behind the search form of `app\models\AvancesMetaOperacion`.
 */
class AvancesMetaOperacionSearch extends AvancesMetaOperacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['avances_meta_operacion_id'], 'integer'],
            [['DANE', 'Departamento', 'Municipio', 'Meta', 'Beneficiarios_En_Operacion', 'Meta_Tiempo_en_servicio', 'Tiempo_en_servicio', 'Avance'], 'safe'],
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
        $query = AvancesMetaOperacion::find();

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
            'avances_meta_operacion_id' => $this->avances_meta_operacion_id,
        ]);

        $query->andFilterWhere(['like', 'DANE', $this->DANE])
            ->andFilterWhere(['like', 'Departamento', $this->Departamento])
            ->andFilterWhere(['like', 'Municipio', $this->Municipio])
            ->andFilterWhere(['like', 'Meta', $this->Meta])
            ->andFilterWhere(['like', 'Beneficiarios_En_Operacion', $this->Beneficiarios_En_Operacion])
            ->andFilterWhere(['like', 'Meta_Tiempo_en_servicio', $this->Meta_Tiempo_en_servicio])
            ->andFilterWhere(['like', 'Tiempo_en_servicio', $this->Tiempo_en_servicio])
            ->andFilterWhere(['like', 'Avance', $this->Avance]);

        return $dataProvider;
    }
}
