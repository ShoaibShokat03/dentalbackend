<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Patients]].
 *
 * @see Patients
 */
class PatientsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Patients[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Patients|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
