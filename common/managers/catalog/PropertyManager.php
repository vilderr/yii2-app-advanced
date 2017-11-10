<?php

namespace common\managers\catalog;

use common\models\catalog\property\Property;
use common\repo\PropertyRepository as Repository;
use common\managers\TransactionManager as Transaction;
use common\collects\catalog\property\PropertyCollection as Collection;

/**
 * Class PropertyManager
 * @package common\managers\catalog
 */
class PropertyManager
{
    private $_repository;
    private $_transaction;

    public function __construct(Repository $repository, Transaction $transaction)
    {
        $this->_repository = $repository;
        $this->_transaction = $transaction;
    }

    public function create(Collection $collection)
    {
        $property = Property::create($collection);
        $this->_transaction->wrap(function () use ($property) {
            $this->_repository->save($property);
        });

        return $property;
    }

    public function edit(Property $property, Collection $collection)
    {
        $property->edit($collection);
        $this->_transaction->wrap(function () use ($property) {
            $this->_repository->save($property);
        });
    }

    public function remove(Property $property)
    {
        $this->_repository->delete($property);
    }
}