<?php

namespace backend\managers;

use backend\models\BackendMenu;
use common\managers\TransactionManager as Transaction;
use backend\collects\BackendMenuCollection as Collection;
use backend\repo\BackendMenuRepository as Repository;

/**
 * Class MenuManager
 * @package backend\managers
 */
class MenuManager
{
    private $_transaction;
    private $_repository;

    /**
     * MenuManager constructor.
     *
     * @param Repository         $repository
     * @param Transaction $transaction
     */
    public function __construct(Repository $repository, Transaction $transaction)
    {
        $this->_transaction = $transaction;
        $this->_repository = $repository;
    }

    /**
     * @param Collection $collection
     *
     * @return BackendMenu
     */
    public function create(Collection $collection)
    {
        $item = BackendMenu::create($collection);

        if ($collection->isRoot()) {
            $item->makeRoot();
        } else {
            $item->appendTo($collection->parent);
        }

        $this->_transaction->wrap(function () use ($item, $collection) {
            $this->_repository->save($item);
        });

        return $item;
    }

    public function edit(BackendMenu $item, Collection $collection)
    {
        $item->edit($collection);

        $this->_transaction->wrap(function () use ($item) {
            $this->_repository->save($item);
        });
    }

    public function remove(BackendMenu $item)
    {
        if (!$item->deleteWithChildren()) {
            throw new \RuntimeException('Ошибка удаления');
        }
    }
}