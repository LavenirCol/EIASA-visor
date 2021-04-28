<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trafico_services_port".
 *
 * @property int $index
 * @property string|null $vlan_id
 * @property string|null $vlan_attr
 * @property string|null $port_type
 * @property int|null $frame
 * @property int|null $slot
 * @property int|null $port
 * @property int|null $vpi
 * @property int|null $vci
 * @property string|null $flow_type
 * @property string|null $flow_para
 * @property int|null $rx
 * @property int|null $tx
 * @property int|null $created_at
 */
class TraficoServicesPort extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trafico_services_port';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['index'], 'required'],
            [['index', 'frame', 'slot', 'port', 'vpi', 'vci', 'rx', 'tx'], 'integer'],
            [['vlan_id', 'vlan_attr', 'port_type', 'flow_type', 'flow_para'], 'string', 'max' => 45]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'index' => 'Index',
            'vlan_id' => 'Vlan ID',
            'vlan_attr' => 'Vlan Attr',
            'port_type' => 'Port Type',
            'frame' => 'Frame',
            'slot' => 'Slot',
            'port' => 'Port',
            'vpi' => 'Vpi',
            'vci' => 'Vci',
            'flow_type' => 'Flow Type',
            'flow_para' => 'Flow Para',
            'rx' => 'Rx',
            'tx' => 'Tx',
            'created_at' => 'Created At',
        ];
    }
}
