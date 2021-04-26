<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trafico_olt_history".
 *
 * @property int $id
 * @property int|null $olt_id
 * @property int|null $vlan
 * @property string|null $serv_port
 * @property string|null $downstream
 * @property string|null $upstream
 * @property string|null $downstream_occupied
 * @property string|null $upstream_occupied
 * @property string|null $created_at
 */
class TraficoOltHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trafico_olt_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['olt_id', 'vlan'], 'integer'],
            [['created_at'], 'safe'],
            [['serv_port', 'downstream', 'upstream', 'downstream_occupied', 'upstream_occupied'], 'string', 'max' => 45],
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
            'vlan' => 'Vlan',
            'serv_port' => 'Serv Port',
            'downstream' => 'Downstream',
            'upstream' => 'Upstream',
            'downstream_occupied' => 'Downstream Occupied',
            'upstream_occupied' => 'Upstream Occupied',
            'created_at' => 'Created At',
        ];
    }
}
