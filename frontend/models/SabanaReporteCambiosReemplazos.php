<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sabana_reporte_cambios_reemplazos".
 *
 * @property int $sabana_reporte_cambios_reemplazos_id
 * @property string|null $Ejecutor
 * @property string|null $Documento_Cliente_Acceso_Old
 * @property string|null $Dane_Mun_ID_Punto_Old
 * @property string|null $Estado_Actual_Old
 * @property string|null $Region_Old
 * @property string|null $Dane_Departamento_Old
 * @property string|null $Departamento_Old
 * @property string|null $Dane_Municipio_Old
 * @property string|null $Municipio_Old
 * @property string|null $Barrio_Old
 * @property string|null $Direccion_Old
 * @property string|null $Estrato_Old
 * @property string|null $Coordenadas_Grados_decimales_Old
 * @property string|null $Nombre_Cliente_Completo_Old
 * @property string|null $Telefono_Old
 * @property string|null $Celular_Old
 * @property string|null $Correo_Electronico_Old
 * @property string|null $VIP_Old
 * @property string|null $Codigo_Proyecto_VIP_Old
 * @property string|null $Nombre_Proyecto_VIP_Old
 * @property string|null $Velocidad_Contratada_MB_Old
 * @property string|null $Meta_Old
 * @property string|null $Tipo_Solucion_UM_Operatividad_Old
 * @property string|null $Operador_Prestante_Old
 * @property string|null $IP_fibra_optica_Old
 * @property string|null $Olt_fibra_optica_Old
 * @property string|null $PuertoOlt_fibra_optica_Old
 * @property string|null $Mac_Onu_fibra_optica_Old
 * @property string|null $Port_Onu_fibra_optica_Old
 * @property string|null $Nodo_red_cobre_Old
 * @property string|null $Armario_red_cobre_Old
 * @property string|null $Red_Primaria_red_cobre_Old
 * @property string|null $Red_Secundaria_red_cobre_Old
 * @property string|null $Nodo_red_hfc_Old
 * @property string|null $Amplificador_red_hfc_Old
 * @property string|null $Tap_Boca_red_hfc_Old
 * @property string|null $Mac_Cpe_Old
 * @property string|null $Documento_Cliente_Acceso_New
 * @property string|null $Region_New
 * @property string|null $Dane_Departamento_New
 * @property string|null $Departamento_New
 * @property string|null $Dane_Municipio_New
 * @property string|null $Municipio_New
 * @property string|null $Barrio_New
 * @property string|null $Direccion_New
 * @property string|null $Estrato_New
 * @property string|null $Coordenadas_Grados_decimales_New
 * @property string|null $Nombre_Cliente_Completo_New
 * @property string|null $Telefono_New
 * @property string|null $Celular_New
 * @property string|null $Correo_Electronico_New
 * @property string|null $VIP_New
 * @property string|null $Codigo_Proyecto_VIP_New
 * @property string|null $Nombre_Proyecto_VIP_New
 * @property string|null $Velocidad_Contratada_MB_New
 * @property string|null $Meta_New
 * @property string|null $Tipo_Solucion_UM_Operatividad_New
 * @property string|null $Operador_Prestante_New
 * @property string|null $IP_fibra_optica_New
 * @property string|null $Olt_fibra_optica_New
 * @property string|null $PuertoOlt_fibra_optica_New
 * @property string|null $Mac_Onu_fibra_optica_New
 * @property string|null $Port_Onu_fibra_optica_New
 * @property string|null $Nodo_red_cobre_New
 * @property string|null $Armario_red_cobre_New
 * @property string|null $Red_Primaria_red_cobre_New
 * @property string|null $Red_Secundaria_red_cobre_New
 * @property string|null $Nodo_red_hfc_New
 * @property string|null $Amplificador_red_hfc_New
 * @property string|null $Tap_Boca_red_hfc_New
 * @property string|null $Mac_Cpe_New
 */
class SabanaReporteCambiosReemplazos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sabana_reporte_cambios_reemplazos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Ejecutor', 'Documento_Cliente_Acceso_Old', 'Dane_Mun_ID_Punto_Old', 'Estado_Actual_Old', 'Region_Old', 'Dane_Departamento_Old', 'Departamento_Old', 'Dane_Municipio_Old', 'Municipio_Old', 'Barrio_Old', 'Direccion_Old', 'Estrato_Old', 'Coordenadas_Grados_decimales_Old', 'Nombre_Cliente_Completo_Old', 'Telefono_Old', 'Celular_Old', 'Correo_Electronico_Old', 'VIP_Old', 'Codigo_Proyecto_VIP_Old', 'Nombre_Proyecto_VIP_Old', 'Velocidad_Contratada_MB_Old', 'Meta_Old', 'Tipo_Solucion_UM_Operatividad_Old', 'Operador_Prestante_Old', 'IP_fibra_optica_Old', 'Olt_fibra_optica_Old', 'PuertoOlt_fibra_optica_Old', 'Mac_Onu_fibra_optica_Old', 'Port_Onu_fibra_optica_Old', 'Nodo_red_cobre_Old', 'Armario_red_cobre_Old', 'Red_Primaria_red_cobre_Old', 'Red_Secundaria_red_cobre_Old', 'Nodo_red_hfc_Old', 'Amplificador_red_hfc_Old', 'Tap_Boca_red_hfc_Old', 'Mac_Cpe_Old', 'Documento_Cliente_Acceso_New', 'Region_New', 'Dane_Departamento_New', 'Departamento_New', 'Dane_Municipio_New', 'Municipio_New', 'Barrio_New', 'Direccion_New', 'Estrato_New', 'Coordenadas_Grados_decimales_New', 'Nombre_Cliente_Completo_New', 'Telefono_New', 'Celular_New', 'Correo_Electronico_New', 'VIP_New', 'Codigo_Proyecto_VIP_New', 'Nombre_Proyecto_VIP_New', 'Velocidad_Contratada_MB_New', 'Meta_New', 'Tipo_Solucion_UM_Operatividad_New', 'Operador_Prestante_New', 'IP_fibra_optica_New', 'Olt_fibra_optica_New', 'PuertoOlt_fibra_optica_New', 'Mac_Onu_fibra_optica_New', 'Port_Onu_fibra_optica_New', 'Nodo_red_cobre_New', 'Armario_red_cobre_New', 'Red_Primaria_red_cobre_New', 'Red_Secundaria_red_cobre_New', 'Nodo_red_hfc_New', 'Amplificador_red_hfc_New', 'Tap_Boca_red_hfc_New', 'Mac_Cpe_New'], 'string', 'max' => 230],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sabana_reporte_cambios_reemplazos_id' => 'Sabana Reporte Cambios Reemplazos ID',
            'Ejecutor' => 'Ejecutor',
            'Documento_Cliente_Acceso_Old' => 'Documento Cliente Acceso Old',
            'Dane_Mun_ID_Punto_Old' => 'Dane Mun Id Punto Old',
            'Estado_Actual_Old' => 'Estado Actual Old',
            'Region_Old' => 'Region Old',
            'Dane_Departamento_Old' => 'Dane Departamento Old',
            'Departamento_Old' => 'Departamento Old',
            'Dane_Municipio_Old' => 'Dane Municipio Old',
            'Municipio_Old' => 'Municipio Old',
            'Barrio_Old' => 'Barrio Old',
            'Direccion_Old' => 'Direccion Old',
            'Estrato_Old' => 'Estrato Old',
            'Coordenadas_Grados_decimales_Old' => 'Coordenadas Grados Decimales Old',
            'Nombre_Cliente_Completo_Old' => 'Nombre Cliente Completo Old',
            'Telefono_Old' => 'Telefono Old',
            'Celular_Old' => 'Celular Old',
            'Correo_Electronico_Old' => 'Correo Electronico Old',
            'VIP_Old' => 'Vip Old',
            'Codigo_Proyecto_VIP_Old' => 'Codigo Proyecto Vip Old',
            'Nombre_Proyecto_VIP_Old' => 'Nombre Proyecto Vip Old',
            'Velocidad_Contratada_MB_Old' => 'Velocidad Contratada Mb Old',
            'Meta_Old' => 'Meta Old',
            'Tipo_Solucion_UM_Operatividad_Old' => 'Tipo Solucion Um Operatividad Old',
            'Operador_Prestante_Old' => 'Operador Prestante Old',
            'IP_fibra_optica_Old' => 'Ip Fibra Optica Old',
            'Olt_fibra_optica_Old' => 'Olt Fibra Optica Old',
            'PuertoOlt_fibra_optica_Old' => 'Puerto Olt Fibra Optica Old',
            'Mac_Onu_fibra_optica_Old' => 'Mac Onu Fibra Optica Old',
            'Port_Onu_fibra_optica_Old' => 'Port Onu Fibra Optica Old',
            'Nodo_red_cobre_Old' => 'Nodo Red Cobre Old',
            'Armario_red_cobre_Old' => 'Armario Red Cobre Old',
            'Red_Primaria_red_cobre_Old' => 'Red Primaria Red Cobre Old',
            'Red_Secundaria_red_cobre_Old' => 'Red Secundaria Red Cobre Old',
            'Nodo_red_hfc_Old' => 'Nodo Red Hfc Old',
            'Amplificador_red_hfc_Old' => 'Amplificador Red Hfc Old',
            'Tap_Boca_red_hfc_Old' => 'Tap Boca Red Hfc Old',
            'Mac_Cpe_Old' => 'Mac Cpe Old',
            'Documento_Cliente_Acceso_New' => 'Documento Cliente Acceso New',
            'Region_New' => 'Region New',
            'Dane_Departamento_New' => 'Dane Departamento New',
            'Departamento_New' => 'Departamento New',
            'Dane_Municipio_New' => 'Dane Municipio New',
            'Municipio_New' => 'Municipio New',
            'Barrio_New' => 'Barrio New',
            'Direccion_New' => 'Direccion New',
            'Estrato_New' => 'Estrato New',
            'Coordenadas_Grados_decimales_New' => 'Coordenadas Grados Decimales New',
            'Nombre_Cliente_Completo_New' => 'Nombre Cliente Completo New',
            'Telefono_New' => 'Telefono New',
            'Celular_New' => 'Celular New',
            'Correo_Electronico_New' => 'Correo Electronico New',
            'VIP_New' => 'Vip New',
            'Codigo_Proyecto_VIP_New' => 'Codigo Proyecto Vip New',
            'Nombre_Proyecto_VIP_New' => 'Nombre Proyecto Vip New',
            'Velocidad_Contratada_MB_New' => 'Velocidad Contratada Mb New',
            'Meta_New' => 'Meta New',
            'Tipo_Solucion_UM_Operatividad_New' => 'Tipo Solucion Um Operatividad New',
            'Operador_Prestante_New' => 'Operador Prestante New',
            'IP_fibra_optica_New' => 'Ip Fibra Optica New',
            'Olt_fibra_optica_New' => 'Olt Fibra Optica New',
            'PuertoOlt_fibra_optica_New' => 'Puerto Olt Fibra Optica New',
            'Mac_Onu_fibra_optica_New' => 'Mac Onu Fibra Optica New',
            'Port_Onu_fibra_optica_New' => 'Port Onu Fibra Optica New',
            'Nodo_red_cobre_New' => 'Nodo Red Cobre New',
            'Armario_red_cobre_New' => 'Armario Red Cobre New',
            'Red_Primaria_red_cobre_New' => 'Red Primaria Red Cobre New',
            'Red_Secundaria_red_cobre_New' => 'Red Secundaria Red Cobre New',
            'Nodo_red_hfc_New' => 'Nodo Red Hfc New',
            'Amplificador_red_hfc_New' => 'Amplificador Red Hfc New',
            'Tap_Boca_red_hfc_New' => 'Tap Boca Red Hfc New',
            'Mac_Cpe_New' => 'Mac Cpe New',
        ];
    }
}
