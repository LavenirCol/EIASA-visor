<?php
namespace app\models;
use Yii;

/**
 * This is the model class for table "sync_history".
 *
 * @property int $id
 * @property string $event
 * @property string $event_date
 * @property int $status
 * @property int $created_at
 */
class SyncHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sync_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event', 'event_date', 'created_at'], 'required'],
            [['status', 'created_at'], 'integer'],
            [['event'], 'string', 'max' => 100],
            [['event_date'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event' => 'Event',
            'event_date' => 'Event Date',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }
}
