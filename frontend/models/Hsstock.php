<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hsstock".
 *
 * @property string $id
 * @property string|null $pid
 * @property string|null $name
 * @property string|null $sku
 * @property string|null $health_reg
 * @property string|null $location
 * @property string|null $city
 * @property string|null $district
 * @property string|null $code
 * @property string|null $lat
 * @property string|null $lng
 * @property string|null $ref
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
            [['id', 'pid', 'name', 'sku', 'health_reg', 'city', 'district', 'code'], 'string', 'max' => 200],
            [['location'], 'string', 'max' => 255],
            [['lat', 'lng'], 'string', 'max' => 50],
            [['ref'], 'string', 'max' => 25],
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
            'name' => Yii::t('app', 'Name'),
            'sku' => Yii::t('app', 'Sku'),
            'health_reg' => Yii::t('app', 'Health Reg'),
            'location' => Yii::t('app', 'Location'),
            'city' => Yii::t('app', 'City'),
            'district' => Yii::t('app', 'District'),
            'code' => Yii::t('app', 'Code'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'ref' => Yii::t('app', 'Ref'),
        ];
    }
}
