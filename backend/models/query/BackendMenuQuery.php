<?php

namespace backend\models\query;

use yii\db\ActiveQuery;
use paulzi\nestedsets\NestedSetsQueryTrait;
use backend\models\BackendMenu;

/**
 * Class BackendMenuQuery
 * @package backend\models\query
 */
class BackendMenuQuery extends ActiveQuery
{
    use NestedSetsQueryTrait;

    /**
     * @inheritdoc
     * @return BackendMenu[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BackendMenu|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}