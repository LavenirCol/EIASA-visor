<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SabanaReporteCambiosReemplazos;

/**
 * SabanaReporteCambiosReemplazosSearch represents the model behind the search form of `app\models\SabanaReporteCambiosReemplazos`.
 */
class SabanaReporteCambiosReemplazosSearch extends SabanaReporteCambiosReemplazos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sabana_reporte_cambios_reemplazos_id'], 'integer'],
            [['Ejecutor', 'Documento_Cliente_Acceso_Old', 'Dane_Mun_ID_Punto_Old', 'Estado_Actual_Old', 'Region_Old', 'Dane_Departamento_Old', 'Departamento_Old', 'Dane_Municipio_Old', 'Municipio_Old', 'Barrio_Old', 'Direccion_Old', 'Estrato_Old', 'Coordenadas_Grados_decimales_Old', 'Nombre_Cliente_Completo_Old', 'Telefono_Old', 'Celular_Old', 'Correo_Electronico_Old', 'VIP_Old', 'Codigo_Proyecto_VIP_Old', 'Nombre_Proyecto_VIP_Old', 'Velocidad_Contratada_MB_Old', 'Meta_Old', 'Tipo_Solucion_UM_Operatividad_Old', 'Operador_Prestante_Old', 'IP_fibra_optica_Old', 'Olt_fibra_optica_Old', 'PuertoOlt_fibra_optica_Old', 'Mac_Onu_fibra_optica_Old', 'Port_Onu_fibra_optica_Old', 'Nodo_red_cobre_Old', 'Armario_red_cobre_Old', 'Red_Primaria_red_cobre_Old', 'Red_Secundaria_red_cobre_Old', 'Nodo_red_hfc_Old', 'Amplificador_red_hfc_Old', 'Tap_Boca_red_hfc_Old', 'Mac_Cpe_Old', 'Documento_Cliente_Acceso_New', 'Region_New', 'Dane_Departamento_New', 'Departamento_New', 'Dane_Municipio_New', 'Municipio_New', 'Barrio_New', 'Direccion_New', 'Estrato_New', 'Coordenadas_Grados_decimales_New', 'Nombre_Cliente_Completo_New', 'Telefono_New', 'Celular_New', 'Correo_Electronico_New', 'VIP_New', 'Codigo_Proyecto_VIP_New', 'Nombre_Proyecto_VIP_New', 'Velocidad_Contratada_MB_New', 'Meta_New', 'Tipo_Solucion_UM_Operatividad_New', 'Operador_Prestante_New', 'IP_fibra_optica_New', 'Olt_fibra_optica_New', 'PuertoOlt_fibra_optica_New', 'Mac_Onu_fibra_optica_New', 'Port_Onu_fibra_optica_New', 'Nodo_red_cobre_New', 'Armario_red_cobre_New', 'Red_Primaria_red_cobre_New', 'Red_Secundaria_red_cobre_New', 'Nodo_red_hfc_New', 'Amplificador_red_hfc_New', 'Tap_Boca_red_hfc_New', 'Mac_Cpe_New'], 'safe'],
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
        $query = SabanaReporteCambiosReemplazos::find();

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
            'sabana_reporte_cambios_reemplazos_id' => $this->sabana_reporte_cambios_reemplazos_id,
        ]);

        $query->andFilterWhere(['like', 'Ejecutor', $this->Ejecutor])
            ->andFilterWhere(['like', 'Documento_Cliente_Acceso_Old', $this->Documento_Cliente_Acceso_Old])
            ->andFilterWhere(['like', 'Dane_Mun_ID_Punto_Old', $this->Dane_Mun_ID_Punto_Old])
            ->andFilterWhere(['like', 'Estado_Actual_Old', $this->Estado_Actual_Old])
            ->andFilterWhere(['like', 'Region_Old', $this->Region_Old])
            ->andFilterWhere(['like', 'Dane_Departamento_Old', $this->Dane_Departamento_Old])
            ->andFilterWhere(['like', 'Departamento_Old', $this->Departamento_Old])
            ->andFilterWhere(['like', 'Dane_Municipio_Old', $this->Dane_Municipio_Old])
            ->andFilterWhere(['like', 'Municipio_Old', $this->Municipio_Old])
            ->andFilterWhere(['like', 'Barrio_Old', $this->Barrio_Old])
            ->andFilterWhere(['like', 'Direccion_Old', $this->Direccion_Old])
            ->andFilterWhere(['like', 'Estrato_Old', $this->Estrato_Old])
            ->andFilterWhere(['like', 'Coordenadas_Grados_decimales_Old', $this->Coordenadas_Grados_decimales_Old])
            ->andFilterWhere(['like', 'Nombre_Cliente_Completo_Old', $this->Nombre_Cliente_Completo_Old])
            ->andFilterWhere(['like', 'Telefono_Old', $this->Telefono_Old])
            ->andFilterWhere(['like', 'Celular_Old', $this->Celular_Old])
            ->andFilterWhere(['like', 'Correo_Electronico_Old', $this->Correo_Electronico_Old])
            ->andFilterWhere(['like', 'VIP_Old', $this->VIP_Old])
            ->andFilterWhere(['like', 'Codigo_Proyecto_VIP_Old', $this->Codigo_Proyecto_VIP_Old])
            ->andFilterWhere(['like', 'Nombre_Proyecto_VIP_Old', $this->Nombre_Proyecto_VIP_Old])
            ->andFilterWhere(['like', 'Velocidad_Contratada_MB_Old', $this->Velocidad_Contratada_MB_Old])
            ->andFilterWhere(['like', 'Meta_Old', $this->Meta_Old])
            ->andFilterWhere(['like', 'Tipo_Solucion_UM_Operatividad_Old', $this->Tipo_Solucion_UM_Operatividad_Old])
            ->andFilterWhere(['like', 'Operador_Prestante_Old', $this->Operador_Prestante_Old])
            ->andFilterWhere(['like', 'IP_fibra_optica_Old', $this->IP_fibra_optica_Old])
            ->andFilterWhere(['like', 'Olt_fibra_optica_Old', $this->Olt_fibra_optica_Old])
            ->andFilterWhere(['like', 'PuertoOlt_fibra_optica_Old', $this->PuertoOlt_fibra_optica_Old])
            ->andFilterWhere(['like', 'Mac_Onu_fibra_optica_Old', $this->Mac_Onu_fibra_optica_Old])
            ->andFilterWhere(['like', 'Port_Onu_fibra_optica_Old', $this->Port_Onu_fibra_optica_Old])
            ->andFilterWhere(['like', 'Nodo_red_cobre_Old', $this->Nodo_red_cobre_Old])
            ->andFilterWhere(['like', 'Armario_red_cobre_Old', $this->Armario_red_cobre_Old])
            ->andFilterWhere(['like', 'Red_Primaria_red_cobre_Old', $this->Red_Primaria_red_cobre_Old])
            ->andFilterWhere(['like', 'Red_Secundaria_red_cobre_Old', $this->Red_Secundaria_red_cobre_Old])
            ->andFilterWhere(['like', 'Nodo_red_hfc_Old', $this->Nodo_red_hfc_Old])
            ->andFilterWhere(['like', 'Amplificador_red_hfc_Old', $this->Amplificador_red_hfc_Old])
            ->andFilterWhere(['like', 'Tap_Boca_red_hfc_Old', $this->Tap_Boca_red_hfc_Old])
            ->andFilterWhere(['like', 'Mac_Cpe_Old', $this->Mac_Cpe_Old])
            ->andFilterWhere(['like', 'Documento_Cliente_Acceso_New', $this->Documento_Cliente_Acceso_New])
            ->andFilterWhere(['like', 'Region_New', $this->Region_New])
            ->andFilterWhere(['like', 'Dane_Departamento_New', $this->Dane_Departamento_New])
            ->andFilterWhere(['like', 'Departamento_New', $this->Departamento_New])
            ->andFilterWhere(['like', 'Dane_Municipio_New', $this->Dane_Municipio_New])
            ->andFilterWhere(['like', 'Municipio_New', $this->Municipio_New])
            ->andFilterWhere(['like', 'Barrio_New', $this->Barrio_New])
            ->andFilterWhere(['like', 'Direccion_New', $this->Direccion_New])
            ->andFilterWhere(['like', 'Estrato_New', $this->Estrato_New])
            ->andFilterWhere(['like', 'Coordenadas_Grados_decimales_New', $this->Coordenadas_Grados_decimales_New])
            ->andFilterWhere(['like', 'Nombre_Cliente_Completo_New', $this->Nombre_Cliente_Completo_New])
            ->andFilterWhere(['like', 'Telefono_New', $this->Telefono_New])
            ->andFilterWhere(['like', 'Celular_New', $this->Celular_New])
            ->andFilterWhere(['like', 'Correo_Electronico_New', $this->Correo_Electronico_New])
            ->andFilterWhere(['like', 'VIP_New', $this->VIP_New])
            ->andFilterWhere(['like', 'Codigo_Proyecto_VIP_New', $this->Codigo_Proyecto_VIP_New])
            ->andFilterWhere(['like', 'Nombre_Proyecto_VIP_New', $this->Nombre_Proyecto_VIP_New])
            ->andFilterWhere(['like', 'Velocidad_Contratada_MB_New', $this->Velocidad_Contratada_MB_New])
            ->andFilterWhere(['like', 'Meta_New', $this->Meta_New])
            ->andFilterWhere(['like', 'Tipo_Solucion_UM_Operatividad_New', $this->Tipo_Solucion_UM_Operatividad_New])
            ->andFilterWhere(['like', 'Operador_Prestante_New', $this->Operador_Prestante_New])
            ->andFilterWhere(['like', 'IP_fibra_optica_New', $this->IP_fibra_optica_New])
            ->andFilterWhere(['like', 'Olt_fibra_optica_New', $this->Olt_fibra_optica_New])
            ->andFilterWhere(['like', 'PuertoOlt_fibra_optica_New', $this->PuertoOlt_fibra_optica_New])
            ->andFilterWhere(['like', 'Mac_Onu_fibra_optica_New', $this->Mac_Onu_fibra_optica_New])
            ->andFilterWhere(['like', 'Port_Onu_fibra_optica_New', $this->Port_Onu_fibra_optica_New])
            ->andFilterWhere(['like', 'Nodo_red_cobre_New', $this->Nodo_red_cobre_New])
            ->andFilterWhere(['like', 'Armario_red_cobre_New', $this->Armario_red_cobre_New])
            ->andFilterWhere(['like', 'Red_Primaria_red_cobre_New', $this->Red_Primaria_red_cobre_New])
            ->andFilterWhere(['like', 'Red_Secundaria_red_cobre_New', $this->Red_Secundaria_red_cobre_New])
            ->andFilterWhere(['like', 'Nodo_red_hfc_New', $this->Nodo_red_hfc_New])
            ->andFilterWhere(['like', 'Amplificador_red_hfc_New', $this->Amplificador_red_hfc_New])
            ->andFilterWhere(['like', 'Tap_Boca_red_hfc_New', $this->Tap_Boca_red_hfc_New])
            ->andFilterWhere(['like', 'Mac_Cpe_New', $this->Mac_Cpe_New]);

        return $dataProvider;
    }
}
