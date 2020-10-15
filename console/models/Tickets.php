<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tickets".
 *
 * @property int $idTicket
 * @property int $id
 * @property string $socid
 * @property string $ref
 * @property string $fk_soc
 * @property string $subject
 * @property string $message
 * @property string $type_label
 * @property string $category_label
 * @property string $severity_label
 * @property string $datec
 * @property string $date_read
 * @property string $date_close
 */
class Tickets extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tickets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'socid', 'ref', 'fk_soc', 'subject', 'message', 'type_label', 'category_label', 'severity_label', 'datec', 'date_read', 'date_close'], 'required'],
            [['id'], 'integer'],
            [['message'], 'string'],
            [['socid', 'ref', 'fk_soc'], 'string', 'max' => 25],
            [['subject', 'type_label', 'category_label', 'severity_label'], 'string', 'max' => 45],
            [['datec', 'date_read', 'date_close'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idTicket' => Yii::t('app', 'Id Ticket'),
            'id' => Yii::t('app', 'ID'),
            'socid' => Yii::t('app', 'Socid'),
            'ref' => Yii::t('app', 'Ref'),
            'fk_soc' => Yii::t('app', 'Fk Soc'),
            'subject' => Yii::t('app', 'Subject'),
            'message' => Yii::t('app', 'Message'),
            'type_label' => Yii::t('app', 'Type Label'),
            'category_label' => Yii::t('app', 'Category Label'),
            'severity_label' => Yii::t('app', 'Severity Label'),
            'datec' => Yii::t('app', 'Datec'),
            'date_read' => Yii::t('app', 'Date Read'),
            'date_close' => Yii::t('app', 'Date Close'),
        ];
    }
}
