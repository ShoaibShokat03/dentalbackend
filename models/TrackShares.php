<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "track_shares".
 *
 * @property int $id
 * @property int $share_id
 * @property int $user_id
 * @property float $percentage
 * @property float $total_amount
 * @property float $percentage_amount
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string $created_at
 * @property string $updated_at
 */
class TrackShares extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'track_shares';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['share_id', 'user_id', 'percentage', 'total_amount', 'percentage_amount'], 'required'],
            [['share_id', 'user_id', 'created_by', 'updated_by'], 'integer'],
            [['percentage', 'total_amount', 'percentage_amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'share_id' => 'Share ID',
            'user_id' => 'User ID',
            'percentage' => 'Percentage',
            'total_amount' => 'Total Amount',
            'percentage_amount' => 'Percentage Amount',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return TrackSharesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TrackSharesQuery(get_called_class());
    }
}
