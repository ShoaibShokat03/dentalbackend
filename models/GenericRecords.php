<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "generic_records".
 *
 * @property int $id
 * @property string $entity_type
 * @property string|null $label
 * @property string|null $description
 * @property string|null $meta
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class GenericRecords extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'generic_records';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_type'], 'required'],
            [['description'], 'string'],
            [['meta', 'created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['entity_type'], 'string', 'max' => 100],
            [['label'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity_type' => 'Entity Type',
            'label' => 'Label',
            'description' => 'Description',
            'meta' => 'Meta',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * {@inheritdoc}
     * @return GenericRecordsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GenericRecordsQuery(get_called_class());
    }
}
