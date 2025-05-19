<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[InvoiceItems]].
 *
 * @see InvoiceItems
 */
class InvoiceItemsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return InvoiceItems[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return InvoiceItems|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
