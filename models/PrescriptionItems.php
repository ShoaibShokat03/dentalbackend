<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prescription_items".
 *
 * @property int $id
 * @property int $prescription_id
 * @property string $medicine_name
 * @property string|null $dosage
 * @property string|null $frequency
 * @property string|null $duration
 * @property string|null $instructions
 */
class PrescriptionItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prescription_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prescription_id', 'medicine_name'], 'required'],
            [['prescription_id'], 'integer'],
            [['instructions'], 'string'],
            [['medicine_name'], 'string', 'max' => 150],
            [['dosage', 'frequency'], 'string', 'max' => 100],
            [['duration'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prescription_id' => 'Prescription ID',
            'medicine_name' => 'Medicine Name',
            'dosage' => 'Dosage',
            'frequency' => 'Frequency',
            'duration' => 'Duration',
            'instructions' => 'Instructions',
        ];
    }

    /**
     * {@inheritdoc}
     * @return PrescriptionItemsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PrescriptionItemsQuery(get_called_class());
    }
}
