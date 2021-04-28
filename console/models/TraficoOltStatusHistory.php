<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trafico_olt_status_history".
 *
 * @property int $id
 * @property int|null $olt_id
 * @property int|null $status
 * @property string|null $created_at
 */
class TraficoOltStatusHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trafico_olt_status_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['olt_id', 'status'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'olt_id' => 'Olt ID',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }
}
