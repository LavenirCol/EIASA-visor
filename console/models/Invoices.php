<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoices".
 *
 * @property int $idInvoice
 * @property int $id
 * @property string $entity
 * @property string $socid
 * @property string $ref
 * @property int $idFolder
 *
 * @property Folder $idFolder0
 */
class Invoices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'entity', 'socid', 'ref', 'idFolder'], 'required'],
            [['id', 'idFolder'], 'integer'],
            [['entity', 'socid'], 'string', 'max' => 4],
            [['ref'], 'string', 'max' => 12],
            [['idFolder'], 'exist', 'skipOnError' => true, 'targetClass' => Folder::className(), 'targetAttribute' => ['idFolder' => 'idfolder']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idInvoice' => Yii::t('app', 'Id Invoice'),
            'id' => Yii::t('app', 'ID'),
            'entity' => Yii::t('app', 'Entity'),
            'socid' => Yii::t('app', 'Socid'),
            'ref' => Yii::t('app', 'Ref'),
            'idFolder' => Yii::t('app', 'Id Folder'),
        ];
    }

    /**
     * Gets query for [[IdFolder0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdFolder0()
    {
        return $this->hasOne(Folder::className(), ['idfolder' => 'idFolder']);
    }
}
