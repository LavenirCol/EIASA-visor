<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "folder".
 *
 * @property int $idfolder
 * @property string $folderName
 * @property int $folderDefault
 * @property int|null $idParentFolder
 * @property string $folderCreationDate
 * @property int $folderCreationUserId
 * @property int $folderReadOnly
 * @property int $idmodule
 *
 * @property Contract[] $contracts
 * @property Module $idmodule0
 */
class Folder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'folder';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['folderName', 'folderCreationDate', 'folderCreationUserId', 'idmodule'], 'required'],
            [['folderDefault', 'idParentFolder', 'folderCreationUserId', 'folderReadOnly', 'idmodule'], 'integer'],
            [['folderCreationDate'], 'safe'],
            [['folderName'], 'string', 'max' => 100],
            [['idmodule'], 'exist', 'skipOnError' => true, 'targetClass' => Module::className(), 'targetAttribute' => ['idmodule' => 'idmodule']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idfolder' => Yii::t('app', 'Idfolder'),
            'folderName' => Yii::t('app', 'Folder Name'),
            'folderDefault' => Yii::t('app', 'Folder Default'),
            'idParentFolder' => Yii::t('app', 'Id Parent Folder'),
            'folderCreationDate' => Yii::t('app', 'Folder Creation Date'),
            'folderCreationUserId' => Yii::t('app', 'Folder Creation User ID'),
            'folderReadOnly' => Yii::t('app', 'Folder Read Only'),
            'idmodule' => Yii::t('app', 'Idmodule'),
        ];
    }

    /**
     * Gets query for [[Contracts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContracts()
    {
        return $this->hasMany(Contract::className(), ['idFolder' => 'idfolder']);
    }

    /**
     * Gets query for [[Idmodule0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdmodule0()
    {
        return $this->hasOne(Module::className(), ['idmodule' => 'idmodule']);
    }
}
