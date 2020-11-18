<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "avances_metas_instalacion".
 *
 * @property int $avances_metas_instalacion_id
 * @property string|null $DANE
 * @property string|null $Departamento
 * @property string|null $Municipio
 * @property string|null $Meta
 * @property string|null $Beneficiarios_Instalados
 * @property string|null $Avance
 */
class AvancesMetasInstalacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'avances_metas_instalacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['DANE', 'Departamento', 'Municipio', 'Meta', 'Beneficiarios_Instalados', 'Avance'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'avances_metas_instalacion_id' => Yii::t('app', 'Avances Metas Instalacion ID'),
            'DANE' => Yii::t('app', 'Dane'),
            'Departamento' => Yii::t('app', 'Departamento'),
            'Municipio' => Yii::t('app', 'Municipio'),
            'Meta' => Yii::t('app', 'Meta'),
            'Beneficiarios_Instalados' => Yii::t('app', 'Beneficiarios Instalados'),
            'Avance' => Yii::t('app', 'Avance'),
        ];
    }
}
