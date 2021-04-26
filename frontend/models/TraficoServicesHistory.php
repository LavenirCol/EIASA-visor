<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trafico_services_history".
 *
 * @property int $id
 * @property int|null $service-port
 * @property int|null $ont_id
 * @property string|null $state
 * @property string|null $sn
 * @property string|null $downstream
 * @property string|null $upstream
 * @property int|null $created_at
 */
class TraficoServicesHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trafico_services_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_port', 'ont_id', 'created_at'], 'integer'],
            [['state', 'sn', 'downstream', 'upstream'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'service_port' => 'Service Port',
            'ont_id' => 'Ont ID',
            'state' => 'State',
            'sn' => 'Sn',
            'downstream' => 'Downstream',
            'upstream' => 'Upstream',
            'created_at' => 'Created At',
        ];
    }
}
