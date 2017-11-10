<?php

namespace common\managers\catalog;

use common\models\catalog\distribution\DistributionProfilePart as Part;
use common\repo\catalog\DistributionProfilePartRepository as Repository;
use common\collects\catalog\distribution\DistributionProfilePartCollection as Collection;
use common\managers\TransactionManager as Transaction;

/**
 * Class DistributionProfilePartManager
 * @package common\managers\catalog
 */
class DistributionProfilePartManager
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
        $part = Part::create($collection);
        $this->_transaction->wrap(function () use ($part) {
            $this->_repository->save($part);
        });

        return $part;
    }

    public function edit(Part $part, Collection $collection)
    {
        $part->edit($part, $collection);
        $this->_transaction->wrap(function () use ($part) {
            $this->_repository->save($part);
        });
    }

    public function remove(Part $part)
    {
        $this->_repository->delete($part);
    }
}