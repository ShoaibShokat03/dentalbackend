<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MedicalCenterInfo]].
 *
 * @see MedicalCenterInfo
 */
class MedicalCenterInfoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return MedicalCenterInfo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MedicalCenterInfo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
