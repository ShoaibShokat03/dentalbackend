<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventory".
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string|null $description
 * @property string|null $code
 * @property int $quantity
 * @property float|null $cost_price
 * @property float|null $selling_price
 * @property string|null $expiry_date
 * @property int $active
 * @property string $created_at
 * @property string $updated_at
 */
class Inventory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'name'], 'required'],
            [['category_id', 'quantity', 'active'], 'integer'],
            [['description'], 'string'],
            [['cost_price', 'selling_price'], 'number'],
            [['expiry_date', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 150],
            [['code'], 'string', 'max' => 100],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'name' => 'Name',
            'description' => 'Description',
            'code' => 'Code',
            'quantity' => 'Quantity',
            'cost_price' => 'Cost Price',
            'selling_price' => 'Selling Price',
            'expiry_date' => 'Expiry Date',
            'active' => 'Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return InventoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InventoryQuery(get_called_class());
    }

    public function getCategory()
    {
        return $this->hasOne(  InventoryCategories::class, ['id' => 'category_id']);
    }

    public function getTransactions()
    {
        return $this->hasMany(InventoryTransactions::class, ['inventory_id' => 'id']);
    }
}
