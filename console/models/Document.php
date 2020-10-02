<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document".
 *
 * @property int $iddocument
 * @property string $name
 * @property string $path
 * @property string $level1name
 * @property string $relativename
 * @property string $fullname
 * @property string $date
 * @property string $size
 * @property string $type
 * @property int $iddocumentType
 * @property int $idFolder
 *
 * @property Documenttype $iddocumentType0
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'path', 'level1name', 'relativename', 'fullname', 'date', 'size', 'type', 'iddocumentType', 'idFolder'], 'required'],
            [['iddocumentType', 'idFolder'], 'integer'],
            [['name', 'level1name', 'relativename'], 'string', 'max' => 255],
            [['path', 'type'], 'string', 'max' => 255],
            [['fullname'], 'string', 'max' => 255],
            [['date'], 'string', 'max' => 20],
            [['size'], 'string', 'max' => 50],
            [['iddocumentType'], 'exist', 'skipOnError' => true, 'targetClass' => Documenttype::className(), 'targetAttribute' => ['iddocumentType' => 'iddocumentType']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddocument' => Yii::t('app', 'Iddocument'),
            'name' => Yii::t('app', 'Name'),
            'path' => Yii::t('app', 'Path'),
            'level1name' => Yii::t('app', 'Level1name'),
            'relativename' => Yii::t('app', 'Relativename'),
            'fullname' => Yii::t('app', 'Fullname'),
            'date' => Yii::t('app', 'Date'),
            'size' => Yii::t('app', 'Size'),
            'type' => Yii::t('app', 'Type'),
            'iddocumentType' => Yii::t('app', 'Iddocument Type'),
            'idFolder' => Yii::t('app', 'Id Folder'),
        ];
    }

    /**
     * Gets query for [[IddocumentType0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIddocumentType0()
    {
        return $this->hasOne(Documenttype::className(), ['iddocumentType' => 'iddocumentType']);
    }
}
