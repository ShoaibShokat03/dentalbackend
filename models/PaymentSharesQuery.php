<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PaymentShares]].
 *
 * @see PaymentShares
 */
class PaymentSharesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PaymentShares[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PaymentShares|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
