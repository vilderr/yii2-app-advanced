<?php


namespace common\listeners\catalog\product;

use Yii;
use yii\caching\Cache;
use yii\caching\TagDependency;
use common\managers\catalog\ProductIndexer;
use common\models\catalog\product\Product;
use common\models\events\EntityPersisted;
use DevGroup\TagDependencyHelper\NamingHelper;

/**
 * Class SearchPersistListener
 * @package backend\listeners\catalog\product
 */
class SearchPersistListener
{
    private $indexer;
    private $cache;

    public function __construct(ProductIndexer $indexer, Cache $cache)
    {
        $this->indexer = $indexer;
        $this->cache = $cache;
    }

    public function handle(EntityPersisted $event)
    {
        if ($event->entity instanceof Product) {
            if ($event->entity->isActive()) {
                $this->indexer->index($event->entity);
            } else {
                $this->indexer->remove($event->entity);
            }
            TagDependency::invalidate($this->cache, NamingHelper::getCommonTag(Product::class));
        }
    }
}