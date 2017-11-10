<?php


namespace common\managers\catalog;

use common\models\catalog\category\Category;
use common\repo\CategoryRepository as Repository;
use common\collects\catalog\category\CategoryCollection as Collection;
use common\managers\TransactionManager;

/**
 * Class CategoryManager
 * @package common\managers\catalog
 */
class CategoryManager
{
    private $_repository;
    private $_transaction;

    public function __construct(Repository $repository, TransactionManager $transaction)
    {
        $this->_repository = $repository;
        $this->_transaction = $transaction;
    }

    /**
     * @param Collection $collection
     *
     * @return Category
     */
    public function create(Collection $collection)
    {
        $category = Category::create($collection);

        $category->appendTo($collection->parent);

        if ($picture = $collection->picture->picture) {
            $category->addPicture($picture);
        }

        $this->_transaction->wrap(function () use ($category, $collection) {
            foreach ($collection->properties->values as $property_id) {
                $category->assignProperty($property_id);
            }
            $this->_repository->save($category);
        });

        return $category;
    }

    /**
     * @param Category   $category
     * @param Collection $collection
     */
    public function edit(Category $category, Collection $collection)
    {
        $category->edit($collection);
        \Yii::info(print_r($collection->properties, 1), 'info');
        if ($picture = $collection->picture->picture) {
            $category->addPicture($picture);
        }

        $this->_transaction->wrap(function () use ($category, $collection) {
            $category->revokeProperties();
            $category->save();

            foreach ($collection->properties->values as $property_id) {
                $category->assignProperty($property_id);
            }

            $this->_repository->save($category);
        });
    }

    public function remove(Category $category)
    {
        $this->_repository->delete($category);
    }
}