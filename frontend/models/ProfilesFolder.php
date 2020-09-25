<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profiles_folder".
 *
 * @property int $idprofilefolder
 * @property int $idProfile
 * @property int $idFolder
 * @property string $creationDate
 * @property int $creationUserId
 *
 * @property Profiles $idProfile0
 */
class ProfilesFolder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profiles_folder';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idProfile', 'idFolder', 'creationDate', 'creationUserId'], 'required'],
            [['idProfile', 'idFolder', 'creationUserId'], 'integer'],
            [['creationDate'], 'safe'],
            [['idProfile'], 'exist', 'skipOnError' => true, 'targetClass' => Profiles::className(), 'targetAttribute' => ['idProfile' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idprofilefolder' => Yii::t('app', 'Idprofilefolder'),
            'idProfile' => Yii::t('app', 'Id Profile'),
            'idFolder' => Yii::t('app', 'Id Folder'),
            'creationDate' => Yii::t('app', 'Creation Date'),
            'creationUserId' => Yii::t('app', 'Creation User ID'),
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
}
