<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[InventoryTransactions]].
 *
 * @see InventoryTransactions
 */
class InventoryTransactionsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return InventoryTransactions[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return InventoryTransactions|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
