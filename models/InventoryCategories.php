<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventory_categories".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $active
 * @property string $created_at
 * @property string $updated_at
 */
class InventoryCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
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
            'description' => 'Description',
            'active' => 'Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return InventoryCategoriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InventoryCategoriesQuery(get_called_class());
    }
    public function getInventories()
    {
        return $this->hasMany(Inventory::class, ['category_id' => 'id']);
    }
}
