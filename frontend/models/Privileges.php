<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "privileges".
 *
 * @property int $idProfile
 * @property int $idOption
 *
 * @property Profiles $idProfile0
 * @property Options $idOption0
 */
class Privileges extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'privileges';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idProfile', 'idOption'], 'required'],
            [['idProfile', 'idOption'], 'integer'],
            [['idProfile', 'idOption'], 'unique', 'targetAttribute' => ['idProfile', 'idOption']],
            [['idProfile'], 'exist', 'skipOnError' => true, 'targetClass' => Profiles::className(), 'targetAttribute' => ['idProfile' => 'id']],
            [['idOption'], 'exist', 'skipOnError' => true, 'targetClass' => Options::className(), 'targetAttribute' => ['idOption' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idProfile' => Yii::t('app', 'Id Profile'),
            'idOption' => Yii::t('app', 'Id Option'),
        ];
    }

    /**
     * Gets query for [[IdProfile0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdProfile0()
    {
        return $this->hasOne(Profiles::className(), ['id' => 'idProfile']);
    }

    /**
     * Gets query for [[IdOption0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdOption0()
    {
        return $this->hasOne(Options::className(), ['id' => 'idOption']);
    }
}
