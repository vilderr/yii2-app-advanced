<?php

namespace common\models\catalog\property;

use yii\db\ActiveQuery;

/**
 * Class PropertyQuery
 * @package common\models\catalog\property
 */
class PropertyQuery extends ActiveQuery
{

    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere('[[status]]=1');
    }

    /**
     * @return $this
     */
    public function filtrable()
    {
        return $this->andWhere('[[filtrable]]=1');
    }

    /**
     * @inheritdoc
     * @return Property[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Property|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}