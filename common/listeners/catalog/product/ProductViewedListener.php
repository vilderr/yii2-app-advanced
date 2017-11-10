<?php


namespace common\listeners\catalog\product;

use common\models\catalog\product\Product;
use common\models\events\EntityViewed;

class ProductViewedListener
{
    public function handle(EntityViewed $event)
    {
        if ($event->entity instanceof Product) {
            $product = $event->entity;

            $product->updateCounters(['show_counter' => 1]);
        }
    }
}