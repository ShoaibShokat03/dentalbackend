<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[GenericEntities]].
 *
 * @see GenericEntities
 */
class GenericEntitiesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return GenericEntities[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return GenericEntities|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
