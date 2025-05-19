<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TrackShares]].
 *
 * @see TrackShares
 */
class TrackSharesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TrackShares[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TrackShares|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
