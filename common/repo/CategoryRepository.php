<?php

namespace common\repo;

use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use common\dispatchers\EventDispatcher;
use common\models\catalog\category\Category;
use common\models\helpers\CategoryHelper;
use common\models\helpers\ModelHelper;
use DevGroup\TagDependencyHelper\NamingHelper;

/**
 * Class CategoryRepository
 * @package common\repo
 */
class CategoryRepository
{
    private $_dispatcher;
    private $_cache;

    public function __construct(EventDispatcher $dispatcher, Cache $cache)
    {
        $this->_dispatcher = $dispatcher;
        $this->_cache = $cache;
    }

    /**
     * @param $id
     *
     * @return Category
     */
    public function get($id)
    {
        if (!$category = Category::findOne($id)) {
            throw new \DomainException('Категория не найдена');
        }

        return $category;
    }

    /**
     * @return Category
     */
    public function getRoot()
    {
        return $this->_cache->getOrSet('root_category', function () {
            return Category::findOne(1);
        }, null, new TagDependency(['tags' => NamingHelper::getObjectTag(Category::class, 1)]));
    }

    /**
     * @param $id
     *
     * @return Category
     * @throws NotFoundHttpException
     */
    public function find($id)
    {
        if (($category = Category::findOne($id)) !== null) {
            return $category;
        } else {
            throw new NotFoundHttpException('Страница не найдена');
        }
    }

    /**
     * @param Category $category
     * @param int      $depth
     * @param bool     $asArray
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDescendants(Category $category, $depth = 1, $asArray = false)
    {
        $query = $category->getDescendants($depth);
        if ($asArray)
            $query->asArray();

        return $query;
    }

    public function getChidrens(Category $category, $asArray = false)
    {
        $query = $category->getChildren();
        if ($asArray)
            $query->asArray();

        return $query;
    }

    public function save(Category $category)
    {
        if (!$category->save()) {
            throw new \RuntimeException('Saving error.');
        }

        $this->_dispatcher->dispatchAll($category->releaseEvents());
    }

    public function delete(Category $category)
    {
        if (!$category->deleteWithChildren()) {
            throw new \RuntimeException('Ошибка удаления категории');
        }

        $this->_dispatcher->dispatchAll($category->releaseEvents());
    }

    public static function tree()
    {
        return ArrayHelper::map(Category::find()->orderBy('lft')->asArray()->all(), 'id', function (array $category) {
            return ($category['depth'] > 0 ? str_repeat('-- ', $category['depth']) . ' ' : '') . $category['name'];
        });
    }

    /**
     * @param $slugPath
     *
     * @return null|Category
     */
    public function findBySlugPath($slugPath)
    {
        if ($slugPath)
            return Category::findOne(['slug_path' => $slugPath, 'global_status' => CategoryHelper::STATUS_ACTIVE]);
        else
            return Category::findOne(1);
    }

    public function getList($depth = null, $loadPicture = true, $asArray = false)
    {
        return $this->_cache->getOrSet(['category_list', 'depth' => $depth, 'loadPicture' => $loadPicture, 'asArray' => $asArray], function () use ($depth, $loadPicture, $asArray) {
            $query = Category::find();
            if ($loadPicture === true) {
                $query->with('picture');
            }
            $query->where(['global_status' => ModelHelper::STATUS_ACTIVE])
                ->andWhere(['>', 'depth', 0]);

            if ($depth)
                $query->andWhere(['<=', 'depth', $depth]);

            $query->orderBy(['lft' => SORT_ASC, 'sort' => SORT_ASC, ]);
            if ($asArray) {
                $query->asArray();
            }

            return $query->all();
        }, null, new TagDependency(['tags' => NamingHelper::getCommonTag(Category::class)]));
    }
}