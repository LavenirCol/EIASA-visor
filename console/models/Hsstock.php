<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hsstock".
 *
 * @property string $id
 * @property string|null $pid
 * @property string|null $uuid
 * @property string|null $name
 * @property string|null $factory
 * @property string|null $model
 * @property string|null $datecreate
 * @property string|null $sku
 * @property string|null $health_reg
 * @property string|null $quantity
 * @property string|null $measure
 * @property string|null $location
 * @property string|null $city
 * @property string|null $city_code
 * @property string|null $district
 * @property string|null $district_code
 * @property string|null $lat
 * @property string|null $lng
 */
class Hsstock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hsstock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'pid', 'name', 'factory', 'model', 'sku', 'health_reg', 'city', 'district'], 'string', 'max' => 200],
            [['uuid', 'datecreate', 'quantity', 'measure', 'city_code', 'district_code'], 'string', 'max' => 45],
            [['location'], 'string', 'max' => 255],
            [['lat', 'lng'], 'string', 'max' => 50],
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
            'pid' => Yii::t('app', 'Pid'),
            'uuid' => Yii::t('app', 'Uuid'),
            'name' => Yii::t('app', 'Name'),
            'factory' => Yii::t('app', 'Factory'),
            'model' => Yii::t('app', 'Model'),
            'datecreate' => Yii::t('app', 'Datecreate'),
            'sku' => Yii::t('app', 'Sku'),
            'health_reg' => Yii::t('app', 'Health Reg'),
            'quantity' => Yii::t('app', 'Quantity'),
            'measure' => Yii::t('app', 'Measure'),
            'location' => Yii::t('app', 'Location'),
            'city' => Yii::t('app', 'City'),
            'city_code' => Yii::t('app', 'City Code'),
            'district' => Yii::t('app', 'District'),
            'district_code' => Yii::t('app', 'District Code'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
        ];
    }
}
