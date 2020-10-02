<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property int $idClient
 * @property string $entity
 * @property string $name
 * @property string $state_id
 * @property string|null $state_code
 * @property string|null $state
 * @property string|null $town
 * @property string|null $email
 * @property string|null $phone
 * @property string $idprof1
 * @property string $code_client
 * @property string $ref
 * @property string $country_id
 * @property string $country_code
 * @property string $country
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idClient', 'entity', 'name', 'state_id', 'idprof1', 'code_client', 'ref', 'country_id', 'country_code', 'country'], 'required'],
            [['idClient'], 'integer'],
            [['entity', 'state_id', 'state_code', 'ref', 'country_id', 'country_code'], 'string', 'max' => 4],
            [['name', 'state', 'town'], 'string', 'max' => 200],
            [['email', 'country'], 'string', 'max' => 100],
            [['phone', 'idprof1'], 'string', 'max' => 20],
            [['code_client'], 'string', 'max' => 12],
            [['idClient'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idClient' => Yii::t('app', 'Id Client'),
            'entity' => Yii::t('app', 'Entity'),
            'name' => Yii::t('app', 'Name'),
            'state_id' => Yii::t('app', 'State ID'),
            'state_code' => Yii::t('app', 'State Code'),
            'state' => Yii::t('app', 'State'),
            'town' => Yii::t('app', 'Town'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'idprof1' => Yii::t('app', 'Idprof1'),
            'code_client' => Yii::t('app', 'Code Client'),
            'ref' => Yii::t('app', 'Ref'),
            'country_id' => Yii::t('app', 'Country ID'),
            'country_code' => Yii::t('app', 'Country Code'),
            'country' => Yii::t('app', 'Country'),
        ];
    }
}
