<?php

namespace common\managers\catalog;

use common\models\catalog\distribution\DistributionProfile as Profile;
use common\repo\catalog\DistributionProfileRepository as Repository;
use common\managers\TransactionManager as Transaction;
use common\collects\catalog\distribution\DistributionProfileCollection as Collection;

/**
 * Class DistributionProfileManager
 * @package common\managers\catalog
 */
class DistributionProfileManager
{
    private $_repository;
    private $_transaction;

    public function __construct(Repository $repository, Transaction $transaction)
    {
        $this->_repository = $repository;
        $this->_transaction = $transaction;
    }

    /**
     * @param Collection $collection
     *
     * @return Profile
     */
    public function create(Collection $collection)
    {
        $profile = Profile::create($collection);
        $this->_transaction->wrap(function () use ($profile) {
            $this->_repository->save($profile);
        });

        return $profile;
    }

    /**
     * @param Profile    $profile
     * @param Collection $collection
     */
    public function edit(Profile $profile, Collection $collection)
    {
        $profile->edit($collection);
        $this->_transaction->wrap(function () use ($profile, $collection) {
            $this->_repository->save($profile);
        });
    }

    /**
     * @param Profile $profile
     */
    public function remove(Profile $profile)
    {
        $this->_repository->delete($profile);
    }
}