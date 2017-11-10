<?php

namespace common\managers\catalog;

use common\models\catalog\product\Product;
use common\repo\ProductRepository as Repository;
use common\collects\catalog\ProductCollection as Collection;
use common\managers\TransactionManager;

/**
 * Class ProductManager
 * @package common\managers
 */
class ProductManager
{
    private $_repository;
    private $_transaction;

    public function __construct(Repository $repository, TransactionManager $transaction)
    {
        $this->_repository = $repository;
        $this->_transaction = $transaction;
    }

    public function create(Collection $collection)
    {
        $product = Product::create($collection);

        if ($picture = $collection->picture->picture) {
            $product->addPicture($picture);
        }

        foreach ($collection->tags->values as $id) {
            $product->assignTag($id);
        }

        foreach ($collection->values as $pid => $valueCollection) {
            foreach ($valueCollection->values as $value) {
                $product->assignValue($valueCollection->property, $value);
            }
        }

        $this->_transaction->wrap(function () use ($product, $collection) {
            $this->_repository->save($product);
        });

        return $product;
    }

    public function edit(Product $product, Collection $collection)
    {
        $product->edit($collection);

        if ($picture = $collection->picture->picture) {
            $product->addPicture($picture);
        }

        $this->_transaction->wrap(function () use ($product, $collection) {
            $product->revokeTags();
            $product->revokeValues();
            $product->save();
            foreach ($collection->tags->values as $id) {
                $product->assignTag($id);
            }

            foreach ($collection->values as $pid => $valueCollection) {
                foreach ($valueCollection->values as $value) {
                    $product->assignValue($valueCollection->property, $value);
                }
            }

            $this->_repository->save($product);
        });
    }

    public function remove(Product $product)
    {
        $this->_repository->delete($product);
    }

    public function getTest()
    {
        return '123';
    }
}