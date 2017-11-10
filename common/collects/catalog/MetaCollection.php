<?php

namespace common\collects\catalog;

use yii\base\Model;
use common\models\catalog\Meta;

/**
 * Class MetaCollection
 * @package backend\collects\catalog
 */
class MetaCollection extends Model
{
    public $title;
    public $description;
    public $keywords;

    public $product_title;
    public $product_description;
    public $product_keywords;

    public function __construct(Meta $meta = null, array $config = [])
    {
        if ($meta) {
            $this->title = $meta->title;
            $this->description = $meta->description;
            $this->keywords = $meta->keywords;

            $this->product_title = $meta->product_title;
            $this->product_description = $meta->product_description;
            $this->product_keywords = $meta->product_keywords;
        }
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'product_title'], 'string', 'max' => 255],
            [['description', 'keywords', 'product_description', 'product_keywords'], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'title'               => 'Заголовок страницы',
            'description'         => 'Описание страницы',
            'keywords'            => 'Ключевые слова',
            'product_title'       => 'Заголовок страницы',
            'product_description' => 'Описание страницы',
            'product_keywords'    => 'Ключевые слова',
        ];
    }
}