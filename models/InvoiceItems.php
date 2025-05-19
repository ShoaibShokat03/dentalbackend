<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice_items".
 *
 * @property int $id
 * @property int $invoice_id
 * @property string $item_type
 * @property string $item_description
 * @property int $quantity
 * @property float $unit_price
 * @property float $discount
 * @property float|null $total_price
 */
class InvoiceItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id', 'item_type', 'item_description'], 'required'],
            [['invoice_id', 'quantity'], 'integer'],
            [['item_type'], 'string'],
            [['unit_price', 'discount', 'total_price'], 'number'],
            [['item_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_id' => 'Invoice ID',
            'item_type' => 'Item Type',
            'item_description' => 'Item Description',
            'quantity' => 'Quantity',
            'unit_price' => 'Unit Price',
            'discount' => 'Discount',
            'total_price' => 'Total Price',
        ];
    }

    /**
     * {@inheritdoc}
     * @return InvoiceItemsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InvoiceItemsQuery(get_called_class());
    }
}
