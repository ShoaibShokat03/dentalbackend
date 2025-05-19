<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PrescriptionItems]].
 *
 * @see PrescriptionItems
 */
class PrescriptionItemsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PrescriptionItems[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PrescriptionItems|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
