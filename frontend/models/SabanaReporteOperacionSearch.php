<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SabanaReporteOperacion;

/**
 * SabanaReporteOperacionSearch represents the model behind the search form of `app\models\SabanaReporteOperacion`.
 */
class SabanaReporteOperacionSearch extends SabanaReporteOperacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sabana_reporte_operacion_id'], 'integer'],
            [['Operador', 'Documento_cliente_acceso', 'Dane_Mun_ID_Punto', 'Estado_actual', 'Region', 'Dane_Departamento', 'Departamento', 'Dane_Municipio', 'Municipio', 'Barrio', 'Direccion', 'Estrato', 'Dificultad__de_acceso_al_municipio', 'Coordenadas_Grados_decimales', 'Nombre_Cliente', 'Telefono', 'Celular', 'Correo_Electronico', 'VIP', 'Codigo_Proyecto_VIP', 'Nombre_Proyecto_VIP', 'Velocidad_Contratada_Downstream', 'Meta', 'Fecha_max_de_cumplimiento_de_meta', 'Tipo_Solucion_UM_Operatividad', 'Operador_Prestante', 'IP', 'Olt', 'PuertoOlt', 'Serial_ONT', 'Port_ONT', 'Nodo', 'Armario', 'Red_Primaria', 'Red_Secundaria', 'Nodo2', 'Amplificador', 'Tap_Boca', 'Mac_Cpe', 'Fecha_Instalado', 'Fecha_Activo', 'Fecha_inicio_operación', 'Fecha_Solicitud_Traslado_PQR', 'Fecha_Inactivo', 'Fecha_Desinstalado', 'Sexo', 'Genero', 'Orientacion_Sexual', 'Educacion_', 'Etnias', 'Discapacidad', 'Estratos', 'Beneficiario_Ley_1699_de_2013', 'SISBEN_IV'], 'safe'],
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
        $query = SabanaReporteOperacion::find();

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
            'sabana_reporte_operacion_id' => $this->sabana_reporte_operacion_id,
        ]);

        $query->andFilterWhere(['like', 'Operador', $this->Operador])
            ->andFilterWhere(['like', 'Documento_cliente_acceso', $this->Documento_cliente_acceso])
            ->andFilterWhere(['like', 'Dane_Mun_ID_Punto', $this->Dane_Mun_ID_Punto])
            ->andFilterWhere(['like', 'Estado_actual', $this->Estado_actual])
            ->andFilterWhere(['like', 'Region', $this->Region])
            ->andFilterWhere(['like', 'Dane_Departamento', $this->Dane_Departamento])
            ->andFilterWhere(['like', 'Departamento', $this->Departamento])
            ->andFilterWhere(['like', 'Dane_Municipio', $this->Dane_Municipio])
            ->andFilterWhere(['like', 'Municipio', $this->Municipio])
            ->andFilterWhere(['like', 'Barrio', $this->Barrio])
            ->andFilterWhere(['like', 'Direccion', $this->Direccion])
            ->andFilterWhere(['like', 'Estrato', $this->Estrato])
            ->andFilterWhere(['like', 'Dificultad__de_acceso_al_municipio', $this->Dificultad__de_acceso_al_municipio])
            ->andFilterWhere(['like', 'Coordenadas_Grados_decimales', $this->Coordenadas_Grados_decimales])
            ->andFilterWhere(['like', 'Nombre_Cliente', $this->Nombre_Cliente])
            ->andFilterWhere(['like', 'Telefono', $this->Telefono])
            ->andFilterWhere(['like', 'Celular', $this->Celular])
            ->andFilterWhere(['like', 'Correo_Electronico', $this->Correo_Electronico])
            ->andFilterWhere(['like', 'VIP', $this->VIP])
            ->andFilterWhere(['like', 'Codigo_Proyecto_VIP', $this->Codigo_Proyecto_VIP])
            ->andFilterWhere(['like', 'Nombre_Proyecto_VIP', $this->Nombre_Proyecto_VIP])
            ->andFilterWhere(['like', 'Velocidad_Contratada_Downstream', $this->Velocidad_Contratada_Downstream])
            ->andFilterWhere(['like', 'Meta', $this->Meta])
            ->andFilterWhere(['like', 'Fecha_max_de_cumplimiento_de_meta', $this->Fecha_max_de_cumplimiento_de_meta])
            ->andFilterWhere(['like', 'Tipo_Solucion_UM_Operatividad', $this->Tipo_Solucion_UM_Operatividad])
            ->andFilterWhere(['like', 'Operador_Prestante', $this->Operador_Prestante])
            ->andFilterWhere(['like', 'IP', $this->IP])
            ->andFilterWhere(['like', 'Olt', $this->Olt])
            ->andFilterWhere(['like', 'PuertoOlt', $this->PuertoOlt])
            ->andFilterWhere(['like', 'Serial_ONT', $this->Serial_ONT])
            ->andFilterWhere(['like', 'Port_ONT', $this->Port_ONT])
            ->andFilterWhere(['like', 'Nodo', $this->Nodo])
            ->andFilterWhere(['like', 'Armario', $this->Armario])
            ->andFilterWhere(['like', 'Red_Primaria', $this->Red_Primaria])
            ->andFilterWhere(['like', 'Red_Secundaria', $this->Red_Secundaria])
            ->andFilterWhere(['like', 'Nodo2', $this->Nodo2])
            ->andFilterWhere(['like', 'Amplificador', $this->Amplificador])
            ->andFilterWhere(['like', 'Tap_Boca', $this->Tap_Boca])
            ->andFilterWhere(['like', 'Mac_Cpe', $this->Mac_Cpe])
            ->andFilterWhere(['like', 'Fecha_Instalado', $this->Fecha_Instalado])
            ->andFilterWhere(['like', 'Fecha_Activo', $this->Fecha_Activo])
            ->andFilterWhere(['like', 'Fecha_inicio_operación', $this->Fecha_inicio_operación])
            ->andFilterWhere(['like', 'Fecha_Solicitud_Traslado_PQR', $this->Fecha_Solicitud_Traslado_PQR])
            ->andFilterWhere(['like', 'Fecha_Inactivo', $this->Fecha_Inactivo])
            ->andFilterWhere(['like', 'Fecha_Desinstalado', $this->Fecha_Desinstalado])
            ->andFilterWhere(['like', 'Sexo', $this->Sexo])
            ->andFilterWhere(['like', 'Genero', $this->Genero])
            ->andFilterWhere(['like', 'Orientacion_Sexual', $this->Orientacion_Sexual])
            ->andFilterWhere(['like', 'Educacion_', $this->Educacion_])
            ->andFilterWhere(['like', 'Etnias', $this->Etnias])
            ->andFilterWhere(['like', 'Discapacidad', $this->Discapacidad])
            ->andFilterWhere(['like', 'Estratos', $this->Estratos])
            ->andFilterWhere(['like', 'Beneficiario_Ley_1699_de_2013', $this->Beneficiario_Ley_1699_de_2013])
            ->andFilterWhere(['like', 'SISBEN_IV', $this->SISBEN_IV]);

        return $dataProvider;
    }
}
