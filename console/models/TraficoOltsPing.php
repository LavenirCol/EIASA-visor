<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trafico_olts_ping".
 *
 * @property int $id
 * @property int $olt_id
 * @property string $created_at
 * @property int|null $bytes_of_data
 * @property int|null $packets_transmitted
 * @property int|null $packets_recived
 * @property int|null $packets_lost_percent
 * @property float|null $rtt_min
 * @property float|null $rtt_avg
 * @property float|null $rtt_max
 * @property float|null $rtt_mdev
 */
class TraficoOltsPing extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trafico_olts_ping';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['olt_id', 'created_at'], 'required'],
            [['olt_id', 'bytes_of_data', 'packets_transmitted', 'packets_recived', 'packets_lost_percent', 'packets_time'], 'integer'],
            [['created_at'], 'safe'],
            [['rtt_min', 'rtt_avg', 'rtt_max', 'rtt_mdev'], 'number'],
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
            'created_at' => 'Created At',
            'bytes_of_data' => 'Bytes Of Data',
            'packets_transmitted' => 'Packets Transmitted',
            'packets_recived' => 'Packets Recived',
            'packets_lost_percent' => 'Packets Lost Percent',
            'packets_time' => 'Packets Time',
            'rtt_min' => 'Rtt Min',
            'rtt_avg' => 'Rtt Avg',
            'rtt_max' => 'Rtt Max',
            'rtt_mdev' => 'Rtt Mdev',
        ];
    }
}
