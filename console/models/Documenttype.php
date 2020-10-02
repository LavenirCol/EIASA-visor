<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "documenttype".
 *
 * @property int $iddocumentType
 * @property string $documentTypeName
 *
 * @property Document[] $documents
 */
class Documenttype extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documenttype';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['documentTypeName'], 'required'],
            [['documentTypeName'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddocumentType' => Yii::t('app', 'Iddocument Type'),
            'documentTypeName' => Yii::t('app', 'Document Type Name'),
        ];
    }

    /**
     * Gets query for [[Documents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['iddocumentType' => 'iddocumentType']);
    }
}
