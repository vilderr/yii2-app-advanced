<?php

namespace frontend\widgets;

use common\models\catalog\category\Category;
use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class CategoryMenu
 * @package frontend\widgets
 */
class CategoryMenu extends Menu
{
    /**
     * @var Category
     */
    public $category;
    private $parent_id = 1;

    public function run()
    {
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }

        if ($this->category)
            $this->parent_id = $this->category->id;

        $items = self::rowsToTree($this->items, 0, $this->parent_id);

        $items = $this->normalizeItems($items, $hasActiveChild);
        if (!empty($items)) {
            $options = $this->options;
            $tag = ArrayHelper::remove($options, 'tag', 'ul');

            echo Html::tag($tag, $this->renderItems($items), $options);
        }
    }

    private static function rowsToTree($rows, $start_index = 0, $current_parent_id = 0)
    {
        $index = $start_index;
        $tree = [];

        while (isset($rows[$index]) === true && ($rows[$index]['category_id'] >= $current_parent_id)) {
            if ($rows[$index]['category_id'] != $current_parent_id) {
                $index++;
                continue;
            }

            /** @var array $item */
            $item = $rows[$index];

            $url = ['/catalog/' . $item['slug_path'] . '/'];

            $tree_item = [
                'label' => $item['name'],
                'url'   => $url,
            ];
            if (empty($url)) {
                unset($tree_item['url']);
            }

            $attributes_to_check = ['icon', 'class'];
            foreach ($attributes_to_check as $attribute) {
                if (array_key_exists($attribute, $item)) {
                    $tree_item[$attribute] = $item[$attribute];
                }
            }

            $index++;
            $tree_item['items'] = static::rowsToTree($rows, $index, $item['id']);

            $tree[] = $tree_item;
        }

        return $tree;
    }
}