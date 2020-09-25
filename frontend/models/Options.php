<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "options".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $url
 * @property int $level
 * @property int $idDepends
 * @property int $order
 * @property string|null $icon
 *
 * @property Privileges[] $privileges
 * @property Profiles[] $idProfiles
 */
class Options extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'options';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name', 'url', 'level', 'idDepends', 'order'], 'required'],
            [['id', 'level', 'idDepends', 'order'], 'integer'],
            [['name', 'url'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 100],
            [['icon'], 'string', 'max' => 45],
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
            'url' => Yii::t('app', 'Url'),
            'level' => Yii::t('app', 'Level'),
            'idDepends' => Yii::t('app', 'Id Depends'),
            'order' => Yii::t('app', 'Order'),
            'icon' => Yii::t('app', 'Icon'),
        ];
    }

    /**
     * Gets query for [[Privileges]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrivileges()
    {
        return $this->hasMany(Privileges::className(), ['idOption' => 'id']);
    }

    /**
     * Gets query for [[IdProfiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdProfiles()
    {
        return $this->hasMany(Profiles::className(), ['id' => 'idProfile'])->viaTable('privileges', ['idOption' => 'id']);
    }
}
