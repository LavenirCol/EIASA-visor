<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "module".
 *
 * @property int $idmodule
 * @property string $moduleName
 * @property int $moduleReadOnly
 * @property string $moduleCreationDate
 * @property int $moduleCreationUserId
 *
 * @property Folder[] $folders
 */
class Module extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'module';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['moduleName', 'moduleCreationDate', 'moduleCreationUserId'], 'required'],
            [['moduleReadOnly', 'moduleCreationUserId'], 'integer'],
            [['moduleCreationDate'], 'safe'],
            [['moduleName'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmodule' => Yii::t('app', 'Idmodule'),
            'moduleName' => Yii::t('app', 'Module Name'),
            'moduleReadOnly' => Yii::t('app', 'Module Read Only'),
            'moduleCreationDate' => Yii::t('app', 'Module Creation Date'),
            'moduleCreationUserId' => Yii::t('app', 'Module Creation User ID'),
        ];
    }

    /**
     * Gets query for [[Folders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFolders()
    {
        return $this->hasMany(Folder::className(), ['idmodule' => 'idmodule']);
    }
}
