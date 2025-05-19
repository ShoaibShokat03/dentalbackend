<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "medical_center_info".
 *
 * @property int $id
 * @property string $name
 * @property string|null $registration_number
 * @property string|null $contact_email
 * @property string|null $contact_phone
 * @property string|null $whatsapp_number
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postal_code
 * @property string|null $country
 * @property string|null $website
 * @property string|null $logo_path
 * @property string|null $favicon_path
 * @property string|null $footer_note
 * @property string $created_at
 * @property string $updated_at
 */
class MedicalCenterInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'medical_center_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['address', 'footer_note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'contact_email', 'website'], 'string', 'max' => 150],
            [['registration_number', 'city', 'state', 'country'], 'string', 'max' => 100],
            [['contact_phone', 'whatsapp_number'], 'string', 'max' => 50],
            [['postal_code'], 'string', 'max' => 20],
            [['logo_path', 'favicon_path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'registration_number' => 'Registration Number',
            'contact_email' => 'Contact Email',
            'contact_phone' => 'Contact Phone',
            'whatsapp_number' => 'Whatsapp Number',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'postal_code' => 'Postal Code',
            'country' => 'Country',
            'website' => 'Website',
            'logo_path' => 'Logo Path',
            'favicon_path' => 'Favicon Path',
            'footer_note' => 'Footer Note',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return MedicalCenterInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MedicalCenterInfoQuery(get_called_class());
    }
}
