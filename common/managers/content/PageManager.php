<?php


namespace common\managers\content;

use common\managers\TransactionManager as Transaction;
use common\repo\content\PageRepository as Repository;
use common\collects\content\PageCollection as Collection;
use common\models\content\Page;


class PageManager
{
    private $_repository;
    private $_transaction;

    /**
     * PageManager constructor.
     *
     * @param Repository         $repository
     * @param Transaction $transaction
     */
    public function __construct(Repository $repository, Transaction $transaction)
    {
        $this->_repository = $repository;
        $this->_transaction = $transaction;
    }

    /**
     * @param Collection $collection
     *
     * @return Page
     */
    public function create(Collection $collection)
    {
        $page = Page::create($collection);

        if (!$collection->parent) {
            $page->makeRoot();
        } else {
            $page->appendTo($collection->parent);
        }

        $this->_transaction->wrap(function () use ($page) {
            $this->_repository->save($page);
        });

        return $page;
    }

    /**
     * @param Page       $page
     * @param Collection $collection
     */
    public function edit(Page $page, Collection $collection)
    {
        $page->edit($collection);
        $this->_transaction->wrap(function () use ($page) {
            $this->_repository->save($page);
        });
    }

    /**
     * @param Page $page
     */
    public function remove(Page $page)
    {
        $this->_repository->delete($page);
    }
}