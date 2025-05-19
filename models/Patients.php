<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "patients".
 *
 * @property int $id
 * @property int $user_id
 * @property string $full_name
 * @property string|null $father_name
 * @property string|null $email
 * @property string|null $contact_number
 * @property string $gender
 * @property int|null $age
 * @property string|null $address
 * @property string|null $medical_history
 * @property string|null $allergies
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class Patients extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'patients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'full_name', 'gender'], 'required'],
            [['user_id', 'age', 'created_by', 'updated_by'], 'integer'],
            [['gender', 'address', 'medical_history', 'allergies'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['full_name', 'father_name', 'email'], 'string', 'max' => 100],
            [['contact_number'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'full_name' => 'Full Name',
            'father_name' => 'Father Name',
            'email' => 'Email',
            'contact_number' => 'Contact Number',
            'gender' => 'Gender',
            'age' => 'Age',
            'address' => 'Address',
            'medical_history' => 'Medical History',
            'allergies' => 'Allergies',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * {@inheritdoc}
     * @return PatientsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PatientsQuery(get_called_class());
    }

    public function getUser(){
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getAppointments()
    {
        return $this->hasMany(PatientAppointments::class, ['patient_id' => 'id']);
    }
}
