<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment_shares".
 *
 * @property int $id
 * @property int $user_id
 * @property float $percentage
 * @property string $date
 * @property int $month
 * @property string $year
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class PaymentShares extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_shares';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'percentage', 'date', 'month', 'year'], 'required'],
            [['user_id', 'month', 'created_by', 'updated_by'], 'integer'],
            [['percentage'], 'number'],
            [['date', 'year', 'created_at', 'updated_at'], 'safe'],
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
            'percentage' => 'Percentage',
            'date' => 'Date',
            'month' => 'Month',
            'year' => 'Year',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return PaymentSharesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PaymentSharesQuery(get_called_class());
    }
}
