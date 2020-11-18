<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SabanaAccesosOperacion;

/**
 * SabanaAccesosOperacionSearch represents the model behind the search form of `app\models\SabanaAccesosOperacion`.
 */
class SabanaAccesosOperacionSearch extends SabanaAccesosOperacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sabana_accesos_operacion_id'], 'integer'],
            [['Operador', 'Documento_Cliente_Acceso', 'Dane_Mun_ID_Punto', 'Estado_Actual', 'Region', 'Dane_Departamento', 'Departamento', 'Dane_Municipio', 'Municipio', 'Barrio', 'Dirección', 'Estrato', 'Dificultad_de_acceso_al_municipio', 'Coordenadas_Grados-decimales', 'Nombre_Cliente_Completo', 'Telefono', 'Celular', 'Correo_Electronico', 'VIP', 'Codigo_Proyecto_VIP', 'Nombre_Proyecto_VIP', 'Velocidad_Contratada_MB', 'Meta', 'Fecha_de_cumplimiento_de_meta', 'Tipo_Solucion_UM_Operatividad', 'Operador_Prestante', 'IP', 'Olt', 'PuertoOlt', 'Serial_ONT', 'Port_ONT', 'Nodo_Cobre', 'Armario', 'Red_Primaria', 'Red_Secundaria', 'Nodo_HFC', 'Amplificador', 'Tap-Boca', 'Mac_Cpe', 'Fecha_Instalado', 'Fecha_Activo', 'Fecha_inicio_operacion', 'Fecha_Solicitud_Traslado_PQR', 'Fecha_Inactivo', 'Fecha_Desinstalado'], 'safe'],
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
        $query = SabanaAccesosOperacion::find();

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
            'sabana_accesos_operacion_id' => $this->sabana_accesos_operacion_id,
        ]);

        $query->andFilterWhere(['like', 'Operador', $this->Operador])
            ->andFilterWhere(['like', 'Documento_Cliente_Acceso', $this->Documento_Cliente_Acceso])
            ->andFilterWhere(['like', 'Dane_Mun_ID_Punto', $this->Dane_Mun_ID_Punto])
            ->andFilterWhere(['like', 'Estado_Actual', $this->Estado_Actual])
            ->andFilterWhere(['like', 'Region', $this->Region])
            ->andFilterWhere(['like', 'Dane_Departamento', $this->Dane_Departamento])
            ->andFilterWhere(['like', 'Departamento', $this->Departamento])
            ->andFilterWhere(['like', 'Dane_Municipio', $this->Dane_Municipio])
            ->andFilterWhere(['like', 'Municipio', $this->Municipio])
            ->andFilterWhere(['like', 'Barrio', $this->Barrio])
            ->andFilterWhere(['like', 'Dirección', $this->Dirección])
            ->andFilterWhere(['like', 'Estrato', $this->Estrato])
            ->andFilterWhere(['like', 'Dificultad_de_acceso_al_municipio', $this->Dificultad_de_acceso_al_municipio])
            ->andFilterWhere(['like', 'Coordenadas_Grados-decimales', $this->Coordenadas_Grados-decimales])
            ->andFilterWhere(['like', 'Nombre_Cliente_Completo', $this->Nombre_Cliente_Completo])
            ->andFilterWhere(['like', 'Telefono', $this->Telefono])
            ->andFilterWhere(['like', 'Celular', $this->Celular])
            ->andFilterWhere(['like', 'Correo_Electronico', $this->Correo_Electronico])
            ->andFilterWhere(['like', 'VIP', $this->VIP])
            ->andFilterWhere(['like', 'Codigo_Proyecto_VIP', $this->Codigo_Proyecto_VIP])
            ->andFilterWhere(['like', 'Nombre_Proyecto_VIP', $this->Nombre_Proyecto_VIP])
            ->andFilterWhere(['like', 'Velocidad_Contratada_MB', $this->Velocidad_Contratada_MB])
            ->andFilterWhere(['like', 'Meta', $this->Meta])
            ->andFilterWhere(['like', 'Fecha_de_cumplimiento_de_meta', $this->Fecha_de_cumplimiento_de_meta])
            ->andFilterWhere(['like', 'Tipo_Solucion_UM_Operatividad', $this->Tipo_Solucion_UM_Operatividad])
            ->andFilterWhere(['like', 'Operador_Prestante', $this->Operador_Prestante])
            ->andFilterWhere(['like', 'IP', $this->IP])
            ->andFilterWhere(['like', 'Olt', $this->Olt])
            ->andFilterWhere(['like', 'PuertoOlt', $this->PuertoOlt])
            ->andFilterWhere(['like', 'Serial_ONT', $this->Serial_ONT])
            ->andFilterWhere(['like', 'Port_ONT', $this->Port_ONT])
            ->andFilterWhere(['like', 'Nodo_Cobre', $this->Nodo_Cobre])
            ->andFilterWhere(['like', 'Armario', $this->Armario])
            ->andFilterWhere(['like', 'Red_Primaria', $this->Red_Primaria])
            ->andFilterWhere(['like', 'Red_Secundaria', $this->Red_Secundaria])
            ->andFilterWhere(['like', 'Nodo_HFC', $this->Nodo_HFC])
            ->andFilterWhere(['like', 'Amplificador', $this->Amplificador])
            ->andFilterWhere(['like', 'Tap-Boca', $this->Tap-Boca])
            ->andFilterWhere(['like', 'Mac_Cpe', $this->Mac_Cpe])
            ->andFilterWhere(['like', 'Fecha_Instalado', $this->Fecha_Instalado])
            ->andFilterWhere(['like', 'Fecha_Activo', $this->Fecha_Activo])
            ->andFilterWhere(['like', 'Fecha_inicio_operacion', $this->Fecha_inicio_operacion])
            ->andFilterWhere(['like', 'Fecha_Solicitud_Traslado_PQR', $this->Fecha_Solicitud_Traslado_PQR])
            ->andFilterWhere(['like', 'Fecha_Inactivo', $this->Fecha_Inactivo])
            ->andFilterWhere(['like', 'Fecha_Desinstalado', $this->Fecha_Desinstalado]);

        return $dataProvider;
    }
}
