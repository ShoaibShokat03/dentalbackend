<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prescriptions".
 *
 * @property int $id
 * @property int $patient_id
 * @property int|null $prescribed_by
 * @property string $prescription_date
 * @property string|null $diagnosis
 * @property string|null $notes
 * @property string $created_at
 * @property string $updated_at
 */
class Prescriptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prescriptions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patient_id'], 'required'],
            [['patient_id', 'prescribed_by'], 'integer'],
            [['prescription_date', 'created_at', 'updated_at'], 'safe'],
            [['diagnosis', 'notes'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'patient_id' => 'Patient ID',
            'prescribed_by' => 'Prescribed By',
            'prescription_date' => 'Prescription Date',
            'diagnosis' => 'Diagnosis',
            'notes' => 'Notes',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return PrescriptionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PrescriptionsQuery(get_called_class());
    }
    public function getPatient()
    {
        return $this->hasOne(Patients::class, ['id' => 'patient_id']);
    }
}
