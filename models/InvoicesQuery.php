<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Invoices]].
 *
 * @see Invoices
 */
class InvoicesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Invoices[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Invoices|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
