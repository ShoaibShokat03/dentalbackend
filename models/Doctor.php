<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "doctor".
 *
 * @property int $id
 * @property string $gender
 * @property string $date_of_birth
 * @property string|null $status
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $specialization
 * @property string|null $qualification
 * @property int|null $experience Experience in years
 * @property float|null $commission_percentage Commission as percentage
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $created_by
 * @property int|null $update_by
 */
class Doctor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doctor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gender', 'date_of_birth'], 'required'],
            [['gender'], 'string'],
            [['date_of_birth', 'created_at', 'updated_at'], 'safe'],
            [['experience', 'created_by', 'update_by'], 'integer'],
            [['commission_percentage'], 'number'],
            [['status'], 'string', 'max' => 20],
            [['phone'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 500],
            [['specialization', 'qualification'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gender' => 'Gender',
            'date_of_birth' => 'Date Of Birth',
            'status' => 'Status',
            'phone' => 'Phone',
            'address' => 'Address',
            'specialization' => 'Specialization',
            'qualification' => 'Qualification',
            'experience' => 'Experience',
            'commission_percentage' => 'Commission Percentage',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'update_by' => 'Update By',
        ];
    }

    /**
     * {@inheritdoc}
     * @return DoctorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DoctorQuery(get_called_class());
    }
}
