<?php

namespace common\managers\content;

use common\models\content\MainSlider as Slide;
use common\repo\content\MainSliderRepository as Repository;
use common\collects\content\MainSliderCollection as Collection;
use common\managers\TransactionManager as Transaction;

/**
 * Class MainSliderManager
 * @package common\managers\content
 */
class MainSliderManager
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
        $slide = Slide::create($collection);

        if ($file = $collection->picture->picture) {
            $slide->addPicture($file);
        }

        $this->_transaction->wrap(function () use ($slide) {
            $this->_repository->save($slide);
        });

        return $slide;
    }

    public function edit(Slide $slide, Collection $collection)
    {
        $slide->edit($collection);

        if ($file = $collection->picture->picture) {
            $slide->addPicture($file);
        }

        $this->_transaction->wrap(function () use ($slide) {
            $this->_repository->save($slide);
        });
    }

    public function remove(Slide $slide)
    {
        $this->_repository->delete($slide);
    }
}