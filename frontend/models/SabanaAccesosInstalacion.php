<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sabana_accesos_instalacion".
 *
 * @property int $sabana_accesos_instalacion_id
 * @property string|null $Operador
 * @property string|null $Documento_Cliente_Acceso
 * @property string|null $Dane_Mun_ID_Punto
 * @property string|null $Estado_Actual
 * @property string|null $Region
 * @property string|null $Dane_Departamento
 * @property string|null $Departamento
 * @property string|null $Dane_Municipio
 * @property string|null $Municipio
 * @property string|null $Barrio
 * @property string|null $Dirección
 * @property string|null $Estrato
 * @property string|null $Dificultad_de_acceso_al_municipio
 * @property string|null $Coordenadas_Grados_decimales
 * @property string|null $Nombre_Cliente_Completo
 * @property string|null $Telefono
 * @property string|null $Celular
 * @property string|null $Correo_Electronico
 * @property string|null $VIP
 * @property string|null $Codigo_Proyecto_VIP
 * @property string|null $Nombre_Proyecto_VIP
 * @property string|null $Velocidad_Contratada_MB
 * @property string|null $Meta
 * @property string|null $Fecha_max_de_cumplimiento_de_meta
 * @property string|null $Dias_pendientes_de_la_fecha_de_cumplimiento
 * @property string|null $FECHA_APROBACION_INTERVENTORIA
 * @property string|null $FECHA_APROBACION_META_SUPERVISION
 * @property string|null $Tipo_Solucion_UM_Operatividad
 * @property string|null $Operador_Prestante
 * @property string|null $IP
 * @property string|null $Olt
 * @property string|null $PuertoOlt
 * @property string|null $Serial_ONT
 * @property string|null $Port_ONT
 * @property string|null $Nodo_Cobre
 * @property string|null $Armario
 * @property string|null $Red_Primaria
 * @property string|null $Red_Secundaria
 * @property string|null $Nodo_HFC
 * @property string|null $Amplificador
 * @property string|null $Tap_Boca
 * @property string|null $Mac_Cpe
 * @property string|null $Fecha_Asignado_o_Presupuestado
 * @property string|null $Fecha_En_proceso_de_Instalacion
 * @property string|null $Fecha_Anulado
 * @property string|null $Fecha_Instalado
 * @property string|null $Fecha_Activo
 * @property string|null $Fecha_aprobacion_de_meta
 */
class SabanaAccesosInstalacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sabana_accesos_instalacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Operador', 'Documento_Cliente_Acceso', 'Dane_Mun_ID_Punto', 'Estado_Actual', 'Region', 'Dane_Departamento', 'Departamento', 'Dane_Municipio', 'Municipio', 'Barrio', 'Dirección', 'Estrato', 'Dificultad_de_acceso_al_municipio', 'Coordenadas_Grados_decimales', 'Nombre_Cliente_Completo', 'Telefono', 'Celular', 'Correo_Electronico', 'VIP', 'Codigo_Proyecto_VIP', 'Nombre_Proyecto_VIP', 'Velocidad_Contratada_MB', 'Meta', 'Fecha_max_de_cumplimiento_de_meta', 'Dias_pendientes_de_la_fecha_de_cumplimiento', 'FECHA_APROBACION_INTERVENTORIA', 'FECHA_APROBACION_META_SUPERVISION', 'Tipo_Solucion_UM_Operatividad', 'Operador_Prestante', 'IP', 'Olt', 'PuertoOlt', 'Serial_ONT', 'Port_ONT', 'Nodo_Cobre', 'Armario', 'Red_Primaria', 'Red_Secundaria', 'Nodo_HFC', 'Amplificador', 'Tap_Boca', 'Mac_Cpe', 'Fecha_Asignado_o_Presupuestado', 'Fecha_En_proceso_de_Instalacion', 'Fecha_Anulado', 'Fecha_Instalado', 'Fecha_Activo', 'Fecha_aprobacion_de_meta'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sabana_accesos_instalacion_id' => Yii::t('app', 'Sabana Accesos Instalacion ID'),
            'Operador' => Yii::t('app', 'Operador'),
            'Documento_Cliente_Acceso' => Yii::t('app', 'Documento Cliente Acceso'),
            'Dane_Mun_ID_Punto' => Yii::t('app', 'Dane Mun Id Punto'),
            'Estado_Actual' => Yii::t('app', 'Estado Actual'),
            'Region' => Yii::t('app', 'Region'),
            'Dane_Departamento' => Yii::t('app', 'Dane Departamento'),
            'Departamento' => Yii::t('app', 'Departamento'),
            'Dane_Municipio' => Yii::t('app', 'Dane Municipio'),
            'Municipio' => Yii::t('app', 'Municipio'),
            'Barrio' => Yii::t('app', 'Barrio'),
            'Dirección' => Yii::t('app', 'Dirección'),
            'Estrato' => Yii::t('app', 'Estrato'),
            'Dificultad_de_acceso_al_municipio' => Yii::t('app', 'Dificultad De Acceso Al Municipio'),
            'Coordenadas_Grados_decimales' => Yii::t('app', 'Coordenadas Grados Decimales'),
            'Nombre_Cliente_Completo' => Yii::t('app', 'Nombre Cliente Completo'),
            'Telefono' => Yii::t('app', 'Telefono'),
            'Celular' => Yii::t('app', 'Celular'),
            'Correo_Electronico' => Yii::t('app', 'Correo Electronico'),
            'VIP' => Yii::t('app', 'Vip'),
            'Codigo_Proyecto_VIP' => Yii::t('app', 'Codigo Proyecto Vip'),
            'Nombre_Proyecto_VIP' => Yii::t('app', 'Nombre Proyecto Vip'),
            'Velocidad_Contratada_MB' => Yii::t('app', 'Velocidad Contratada Mb'),
            'Meta' => Yii::t('app', 'Meta'),
            'Fecha_max_de_cumplimiento_de_meta' => Yii::t('app', 'Fecha Max De Cumplimiento De Meta'),
            'Dias_pendientes_de_la_fecha_de_cumplimiento' => Yii::t('app', 'Dias Pendientes De La Fecha De Cumplimiento'),
            'FECHA_APROBACION_INTERVENTORIA' => Yii::t('app', 'Fecha Aprobacion Interventoria'),
            'FECHA_APROBACION_META_SUPERVISION' => Yii::t('app', 'Fecha Aprobacion Meta Supervision'),
            'Tipo_Solucion_UM_Operatividad' => Yii::t('app', 'Tipo Solucion Um Operatividad'),
            'Operador_Prestante' => Yii::t('app', 'Operador Prestante'),
            'IP' => Yii::t('app', 'Ip'),
            'Olt' => Yii::t('app', 'Olt'),
            'PuertoOlt' => Yii::t('app', 'Puerto Olt'),
            'Serial_ONT' => Yii::t('app', 'Serial Ont'),
            'Port_ONT' => Yii::t('app', 'Port Ont'),
            'Nodo_Cobre' => Yii::t('app', 'Nodo Cobre'),
            'Armario' => Yii::t('app', 'Armario'),
            'Red_Primaria' => Yii::t('app', 'Red Primaria'),
            'Red_Secundaria' => Yii::t('app', 'Red Secundaria'),
            'Nodo_HFC' => Yii::t('app', 'Nodo Hfc'),
            'Amplificador' => Yii::t('app', 'Amplificador'),
            'Tap_Boca' => Yii::t('app', 'Tap Boca'),
            'Mac_Cpe' => Yii::t('app', 'Mac Cpe'),
            'Fecha_Asignado_o_Presupuestado' => Yii::t('app', 'Fecha Asignado O Presupuestado'),
            'Fecha_En_proceso_de_Instalacion' => Yii::t('app', 'Fecha En Proceso De Instalacion'),
            'Fecha_Anulado' => Yii::t('app', 'Fecha Anulado'),
            'Fecha_Instalado' => Yii::t('app', 'Fecha Instalado'),
            'Fecha_Activo' => Yii::t('app', 'Fecha Activo'),
            'Fecha_aprobacion_de_meta' => Yii::t('app', 'Fecha Aprobacion De Meta'),
        ];
    }
}
