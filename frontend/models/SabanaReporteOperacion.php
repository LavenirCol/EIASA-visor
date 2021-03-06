<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sabana_reporte_operacion".
 *
 * @property int $sabana_reporte_operacion_id
 * @property string|null $Operador
 * @property string|null $Documento_cliente_acceso
 * @property string|null $Dane_Mun_ID_Punto
 * @property string|null $Estado_actual
 * @property string|null $Region
 * @property string|null $Dane_Departamento
 * @property string|null $Departamento
 * @property string|null $Dane_Municipio
 * @property string|null $Municipio
 * @property string|null $Barrio
 * @property string|null $Direccion
 * @property string|null $Estrato
 * @property string|null $Dificultad__de_acceso_al_municipio
 * @property string|null $Coordenadas_Grados_decimales
 * @property string|null $Nombre_Cliente
 * @property string|null $Telefono
 * @property string|null $Celular
 * @property string|null $Correo_Electronico
 * @property string|null $VIP
 * @property string|null $Codigo_Proyecto_VIP
 * @property string|null $Nombre_Proyecto_VIP
 * @property string|null $Velocidad_Contratada_Downstream
 * @property string|null $Meta
 * @property string|null $Fecha_max_de_cumplimiento_de_meta
 * @property string|null $Tipo_Solucion_UM_Operatividad
 * @property string|null $Operador_Prestante
 * @property string|null $IP
 * @property string|null $Olt
 * @property string|null $PuertoOlt
 * @property string|null $Serial_ONT
 * @property string|null $Port_ONT
 * @property string|null $Nodo
 * @property string|null $Armario
 * @property string|null $Red_Primaria
 * @property string|null $Red_Secundaria
 * @property string|null $Nodo2
 * @property string|null $Amplificador
 * @property string|null $Tap_Boca
 * @property string|null $Mac_Cpe
 * @property string|null $Fecha_Instalado
 * @property string|null $Fecha_Activo
 * @property string|null $Fecha_inicio_operación
 * @property string|null $Fecha_Solicitud_Traslado_PQR
 * @property string|null $Fecha_Inactivo
 * @property string|null $Fecha_Desinstalado
 * @property string|null $Sexo
 * @property string|null $Genero
 * @property string|null $Orientacion_Sexual
 * @property string|null $Educacion_
 * @property string|null $Etnias
 * @property string|null $Discapacidad
 * @property string|null $Estratos
 * @property string|null $Beneficiario_Ley_1699_de_2013
 * @property string|null $SISBEN_IV
 */
class SabanaReporteOperacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sabana_reporte_operacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Operador', 'Documento_cliente_acceso', 'Dane_Mun_ID_Punto', 'Estado_actual', 'Region', 'Dane_Departamento', 'Departamento', 'Dane_Municipio', 'Municipio', 'Barrio', 'Direccion', 'Estrato', 'Dificultad__de_acceso_al_municipio', 'Coordenadas_Grados_decimales', 'Nombre_Cliente', 'Telefono', 'Celular', 'Correo_Electronico', 'VIP', 'Codigo_Proyecto_VIP', 'Nombre_Proyecto_VIP', 'Velocidad_Contratada_Downstream', 'Meta', 'Fecha_max_de_cumplimiento_de_meta', 'Tipo_Solucion_UM_Operatividad', 'Operador_Prestante', 'IP', 'Olt', 'PuertoOlt', 'Serial_ONT', 'Port_ONT', 'Nodo', 'Armario', 'Red_Primaria', 'Red_Secundaria', 'Nodo2', 'Amplificador', 'Tap_Boca', 'Mac_Cpe', 'Fecha_Instalado', 'Fecha_Activo', 'Fecha_inicio_operación', 'Fecha_Solicitud_Traslado_PQR', 'Fecha_Inactivo', 'Fecha_Desinstalado', 'Sexo', 'Genero', 'Orientacion_Sexual', 'Educacion_', 'Etnias', 'Discapacidad', 'Estratos', 'Beneficiario_Ley_1699_de_2013', 'SISBEN_IV'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sabana_reporte_operacion_id' => 'Sabana Reporte Operacion ID',
            'Operador' => 'Operador',
            'Documento_cliente_acceso' => 'Documento Cliente Acceso',
            'Dane_Mun_ID_Punto' => 'Dane Mun Id Punto',
            'Estado_actual' => 'Estado Actual',
            'Region' => 'Region',
            'Dane_Departamento' => 'Dane Departamento',
            'Departamento' => 'Departamento',
            'Dane_Municipio' => 'Dane Municipio',
            'Municipio' => 'Municipio',
            'Barrio' => 'Barrio',
            'Direccion' => 'Direccion',
            'Estrato' => 'Estrato',
            'Dificultad__de_acceso_al_municipio' => 'Dificultad De Acceso Al Municipio',
            'Coordenadas_Grados_decimales' => 'Coordenadas Grados Decimales',
            'Nombre_Cliente' => 'Nombre Cliente',
            'Telefono' => 'Telefono',
            'Celular' => 'Celular',
            'Correo_Electronico' => 'Correo Electronico',
            'VIP' => 'Vip',
            'Codigo_Proyecto_VIP' => 'Codigo Proyecto Vip',
            'Nombre_Proyecto_VIP' => 'Nombre Proyecto Vip',
            'Velocidad_Contratada_Downstream' => 'Velocidad Contratada Downstream',
            'Meta' => 'Meta',
            'Fecha_max_de_cumplimiento_de_meta' => 'Fecha Max De Cumplimiento De Meta',
            'Tipo_Solucion_UM_Operatividad' => 'Tipo Solucion Um Operatividad',
            'Operador_Prestante' => 'Operador Prestante',
            'IP' => 'Ip',
            'Olt' => 'Olt',
            'PuertoOlt' => 'Puerto Olt',
            'Serial_ONT' => 'Serial Ont',
            'Port_ONT' => 'Port Ont',
            'Nodo' => 'Nodo',
            'Armario' => 'Armario',
            'Red_Primaria' => 'Red Primaria',
            'Red_Secundaria' => 'Red Secundaria',
            'Nodo2' => 'Nodo2',
            'Amplificador' => 'Amplificador',
            'Tap_Boca' => 'Tap Boca',
            'Mac_Cpe' => 'Mac Cpe',
            'Fecha_Instalado' => 'Fecha Instalado',
            'Fecha_Activo' => 'Fecha Activo',
            'Fecha_inicio_operación' => 'Fecha Inicio Operación',
            'Fecha_Solicitud_Traslado_PQR' => 'Fecha Solicitud Traslado Pqr',
            'Fecha_Inactivo' => 'Fecha Inactivo',
            'Fecha_Desinstalado' => 'Fecha Desinstalado',
            'Sexo' => 'Sexo',
            'Genero' => 'Genero',
            'Orientacion_Sexual' => 'Orientacion Sexual',
            'Educacion_' => 'Educacion',
            'Etnias' => 'Etnias',
            'Discapacidad' => 'Discapacidad',
            'Estratos' => 'Estratos',
            'Beneficiario_Ley_1699_de_2013' => 'Beneficiario Ley 1699 De 2013',
            'SISBEN_IV' => 'Sisben Iv',
        ];
    }
}
