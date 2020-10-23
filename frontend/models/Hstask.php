<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hstask".
 *
 * @property string $uuid
 * @property string|null $datecreate
 * @property string|null $dateupdate
 * @property string|null $reference
 * @property string|null $template
 * @property string|null $address
 * @property string|null $city
 * @property string|null $district
 * @property string|null $code
 * @property string|null $lat
 * @property string|null $lng
 * @property string|null $status
 * @property string|null $pdf
 */
class Hstask extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hstask';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid'], 'required'],
            [['uuid', 'datecreate', 'dateupdate', 'reference', 'template', 'city', 'district', 'code'], 'string', 'max' => 200],
            [['address', 'pdf'], 'string', 'max' => 255],
            [['lat', 'lng'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 25],
            [['uuid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'uuid' => Yii::t('app', 'Uuid'),
            'datecreate' => Yii::t('app', 'Datecreate'),
            'dateupdate' => Yii::t('app', 'Dateupdate'),
            'reference' => Yii::t('app', 'Reference'),
            'template' => Yii::t('app', 'Template'),
            'address' => Yii::t('app', 'Address'),
            'city' => Yii::t('app', 'City'),
            'district' => Yii::t('app', 'District'),
            'code' => Yii::t('app', 'Code'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'status' => Yii::t('app', 'Status'),
            'pdf' => Yii::t('app', 'Pdf'),
        ];
    }
}
