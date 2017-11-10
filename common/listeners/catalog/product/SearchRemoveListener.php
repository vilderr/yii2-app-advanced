<?php


namespace common\listeners\catalog\product;

use yii\caching\Cache;
use yii\caching\TagDependency;
use common\managers\catalog\ProductIndexer;
use common\models\catalog\product\Product;
use common\models\events\EntityRemoved;
use DevGroup\TagDependencyHelper\NamingHelper;

/**
 * Class SearchRemoveListener
 * @package common\listeners\catalog\product
 */
class SearchRemoveListener
{
    private $indexer;
    private $cache;

    public function __construct(ProductIndexer $indexer, Cache $cache)
    {
        $this->indexer = $indexer;
        $this->cache = $cache;
    }

    public function handle(EntityRemoved $event)
    {
        if ($event->entity instanceof Product) {
            $this->indexer->remove($event->entity);
            TagDependency::invalidate($this->cache, NamingHelper::getCommonTag(Product::class));
        }
    }
}