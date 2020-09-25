<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profiles".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 *
 * @property Privileges[] $privileges
 * @property Options[] $idOptions
 * @property ProfilesFolder[] $profilesFolders
 */
class Profiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['description'], 'string', 'max' => 60],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * Gets query for [[Privileges]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrivileges()
    {
        return $this->hasMany(Privileges::className(), ['idProfile' => 'id']);
    }

    /**
     * Gets query for [[IdOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdOptions()
    {
        return $this->hasMany(Options::className(), ['id' => 'idOption'])->viaTable('privileges', ['idProfile' => 'id']);
    }

    /**
     * Gets query for [[ProfilesFolders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfilesFolders()
    {
        return $this->hasMany(ProfilesFolder::className(), ['idProfile' => 'id']);
    }
}
