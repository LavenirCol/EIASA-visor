<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "avances_meta_operacion".
 *
 * @property int $avances_meta_operacion_id
 * @property string|null $DANE
 * @property string|null $Departamento
 * @property string|null $Municipio
 * @property string|null $Meta
 * @property string|null $Beneficiarios_En_Operacion
 * @property string|null $Meta_Tiempo_en_servicio
 * @property string|null $Tiempo_en_servicio
 * @property string|null $Avance
 */
class AvancesMetaOperacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'avances_meta_operacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['DANE', 'Departamento', 'Municipio', 'Meta', 'Beneficiarios_En_Operacion', 'Meta_Tiempo_en_servicio', 'Tiempo_en_servicio', 'Avance'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'avances_meta_operacion_id' => Yii::t('app', 'Avances Meta Operacion ID'),
            'DANE' => Yii::t('app', 'Dane'),
            'Departamento' => Yii::t('app', 'Departamento'),
            'Municipio' => Yii::t('app', 'Municipio'),
            'Meta' => Yii::t('app', 'Meta'),
            'Beneficiarios_En_Operacion' => Yii::t('app', 'Beneficiarios En Operacion'),
            'Meta_Tiempo_en_servicio' => Yii::t('app', 'Meta Tiempo En Servicio'),
            'Tiempo_en_servicio' => Yii::t('app', 'Tiempo En Servicio'),
            'Avance' => Yii::t('app', 'Avance'),
        ];
    }
}
