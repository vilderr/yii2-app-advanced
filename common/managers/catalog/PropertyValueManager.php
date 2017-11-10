<?php

namespace common\managers\catalog;

use common\repo\PropertyValueRepository as Repository;
use common\collects\catalog\property\PropertyValueCollection as Collection;
use common\models\catalog\property\PropertyValue;

class PropertyValueManager
{
    private $_repository;

    public function __construct(Repository $repository)
    {
        $this->_repository = $repository;
    }

    public function create(Collection $collection)
    {
        $value = PropertyValue::create($collection);
        $this->_repository->save($value);

        return $value;
    }

    public function edit(PropertyValue $value, Collection $collection)
    {
        $value->edit($collection);
        $this->_repository->save($value);
    }

    public function remove(PropertyValue $value)
    {
        $this->_repository->delete($value);
    }
}