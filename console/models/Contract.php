<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contract".
 *
 * @property int $idContract
 * @property string $entityID
 * @property string $socID
 * @property string $ref
 * @property string $fkSoc
 * @property int $idFolder
 *
 * @property Folder $idFolder0
 */
class Contract extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contract';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idContract', 'entity', 'socid', 'ref', 'fk_soc', 'idFolder'], 'required'],
            [['idContract', 'idFolder'], 'integer'],
            [['entity', 'socid', 'fk_soc'], 'string', 'max' => 4],
            [['ref'], 'string', 'max' => 12],
            [['idContract'], 'unique'],
            [['idFolder'], 'exist', 'skipOnError' => true, 'targetClass' => Folder::className(), 'targetAttribute' => ['idFolder' => 'idfolder']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idContract' => Yii::t('app', 'Id Contract'),
            'entity' => Yii::t('app', 'Entity ID'),
            'socid' => Yii::t('app', 'Soc ID'),
            'ref' => Yii::t('app', 'Ref'),
            'fk_soc' => Yii::t('app', 'Fk Soc'),
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
