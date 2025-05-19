<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "generic_entities".
 *
 * @property int $id
 * @property string $entity_name
 * @property string $entity_type
 * @property int|null $active
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class GenericEntities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'generic_entities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_name', 'entity_type'], 'required'],
            [['active', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['entity_name'], 'string', 'max' => 150],
            [['entity_type'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity_name' => 'Entity Name',
            'entity_type' => 'Entity Type',
            'active' => 'Active',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return GenericEntitiesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GenericEntitiesQuery(get_called_class());
    }
}
