<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoices".
 *
 * @property int $id
 * @property int $patient_id
 * @property int|null $issued_by
 * @property string $invoice_number
 * @property string $invoice_date
 * @property float $total_amount
 * @property float $discount_amount
 * @property float $net_amount
 * @property int $paid
 * @property string|null $payment_method
 * @property string|null $notes
 * @property string $created_at
 * @property string $updated_at
 */
class Invoices extends \yii\db\ActiveRecord
{

    public $due_amount;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patient_id', 'invoice_number'], 'required'],
            [['patient_id', 'issued_by', 'paid'], 'integer'],
            [['invoice_date', 'created_at', 'updated_at'], 'safe'],
            [['total_amount', 'discount_amount', 'net_amount'], 'number'],
            [['payment_method', 'notes'], 'string'],
            [['invoice_number'], 'string', 'max' => 100],
            [['invoice_number'], 'unique'],
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
            'issued_by' => 'Issued By',
            'invoice_number' => 'Invoice Number',
            'invoice_date' => 'Invoice Date',
            'total_amount' => 'Total Amount',
            'discount_amount' => 'Discount Amount',
            'net_amount' => 'Net Amount',
            'paid' => 'Paid',
            'payment_method' => 'Payment Method',
            'notes' => 'Notes',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return InvoicesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InvoicesQuery(get_called_class());
    }
    public function getItems()
    {
        return $this->hasMany(InvoiceItems::class, ['invoice_id' => 'id']);
    }
    public function getPatient()
    {
        return $this->hasOne(Patients::class, ['id' => 'patient_id']);
    }
    public function getDoctor()
    {
        return $this->hasOne(User::class, ['id' => 'doctor_id']);

    }
    public function getPrescription()
    {
        return $this->hasOne(User::class, ['id' => 'doctor_id']);

    }
}
