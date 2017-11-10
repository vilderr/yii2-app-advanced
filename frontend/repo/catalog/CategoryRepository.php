<?php

namespace frontend\repo\catalog;

use yii\caching\Cache;
use yii\caching\TagDependency;
use common\models\helpers\CategoryHelper;
use common\models\catalog\category\Category;
use common\models\helpers\ModelHelper;
use DevGroup\TagDependencyHelper\NamingHelper;

/**
 * Class CategoryRepository
 * @package frontend\repo\catalog
 */
class CategoryRepository
{
    private $_cache;

    public function __construct(Cache $cache)
    {
        $this->_cache = $cache;
    }

    /**
     * @param $slugPath
     *
     * @return null|Category
     */
    public function findBySlugPath($slugPath)
    {
        return Category::findOne(['slug_path' => $slugPath, 'global_status' => CategoryHelper::STATUS_ACTIVE]);
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

            $query->orderBy(['sort' => SORT_ASC, 'lft' => SORT_ASC]);
            if ($asArray) {
                $query->asArray();
            }

            return $query->all();
        }, null, new TagDependency(['tags' => NamingHelper::getCommonTag(Category::class)]));
    }
}