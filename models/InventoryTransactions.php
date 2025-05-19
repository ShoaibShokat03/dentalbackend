<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventory_transactions".
 *
 * @property int $id
 * @property int $inventory_id
 * @property string $transaction_type
 * @property int $quantity
 * @property float|null $cost_price
 * @property float|null $sell_price
 * @property float|null $total_cost
 * @property string|null $reason
 * @property int|null $created_by
 * @property string $created_at
 */
class InventoryTransactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inventory_id', 'transaction_type', 'quantity'], 'required'],
            [['inventory_id', 'quantity', 'created_by'], 'integer'],
            [['transaction_type', 'reason'], 'string'],
            [['cost_price', 'sell_price', 'total_cost'], 'number'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inventory_id' => 'Inventory ID',
            'transaction_type' => 'Transaction Type',
            'quantity' => 'Quantity',
            'cost_price' => 'Cost Price',
            'sell_price' => 'Sell Price',
            'total_cost' => 'Total Cost',
            'reason' => 'Reason',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return InventoryTransactionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InventoryTransactionsQuery(get_called_class());
    }

    public function getInventory()
    {
        return $this->hasOne(Inventory::class, ['id' => 'inventory_id']);
    }
}
