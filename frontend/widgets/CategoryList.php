<?php

namespace frontend\widgets;

use frontend\widgets\assets\CategoryListAsset;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\models\catalog\category\Category;

class CategoryList extends Widget
{
    public $options = [
        'class' => 'category-top-list',
    ];
    public $items = [];
    public $itemOptions = [];
    public $itemTemplate = '<a href="{url}"><span style="background: url({image})"></span>{label}</a>';

    public function run()
    {
        if (!empty($this->items)) {
            CategoryListAsset::register($this->view);

            $options = $this->options;
            $tag = ArrayHelper::remove($options, 'tag', 'ul');

            echo Html::tag($tag, $this->renderItems($this->items), $options);
        }
    }

    protected function renderItems($items)
    {
        $lines = [];
        foreach ($items as $item) {
            $options = $this->itemOptions;
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $link = $this->renderItem($item);

            $lines[] = Html::tag($tag, $link, $options);
        }

        return implode("\n", $lines);
    }

    protected function renderItem(Category $item)
    {
        return strtr($this->itemTemplate, [
            '{url}'   => Html::encode('/catalog/'.$item->slug_path.'/'),
            '{image}' => $item->picture ? $item->picture->getThumbFileUrl('file', 'picture') : '',
            '{label}' => $item->name,
        ]);
    }
}