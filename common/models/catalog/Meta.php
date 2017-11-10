<?php

namespace common\models\catalog;

/**
 * Class Meta
 * @package common\models\catalog
 */
class Meta extends \common\models\Meta
{
    public $product_title;
    public $product_description;
    public $product_keywords;

    public function __construct($title, $description, $keywords, $product_title, $product_description, $product_keywords)
    {
        parent::__construct($title, $description, $keywords);

        $this->product_title = $product_title;
        $this->product_description = $product_description;
        $this->product_keywords = $product_keywords;
    }
}