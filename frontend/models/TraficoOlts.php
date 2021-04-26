<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trafico_olts".
 *
 * @property int $id
 * @property string $poblacion
 * @property string $wan_olt
 * @property int|null $activo
 */
class TraficoOlts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trafico_olts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'poblacion', 'wan_olt'], 'required'],
            [['id', 'activo'], 'integer'],
            [['poblacion'], 'string', 'max' => 150],
            [['wan_olt'], 'string', 'max' => 45],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'poblacion' => 'Poblacion',
            'wan_olt' => 'Wan Olt',
            'activo' => 'Activo',
        ];
    }
}
