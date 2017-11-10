<?php

namespace frontend\widgets;

use frontend\widgets\assets\SmartFilterAsset;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\Widget;
use yii\helpers\Url;

/**
 * Class SmartFilter
 * @package frontend\widgets
 */
class SmartFilter extends Widget
{
    public $options = [];
    public $category;
    public $sectionOptions = [];
    public $items = [];
    public $title = 'Фильтр';
    public $isSet = false;

    public function run()
    {
        if (!empty($this->items)) {
            SmartFilterAsset::register($this->view);
            $options = $this->options;

            $result = '';
            $result .= Html::beginTag('div', $options);
            $result .= Html::tag('div', $this->title, ['class' => 'title']);
            $result .= Html::tag('ul', $this->renderItems(), ['class' => 'sections']);
            if ($this->isSet)
            {
                $result .= Html::tag('div', Html::a('<span>Сбросить фильтр</span>', Url::to(['/catalog/category', 'id' => $this->category->id]), ['class' => 'reset-filter']), ['class' => 'filter-footer']);
            }
            $result .= Html::endTag('div');

            echo $result;
        }
    }

    protected function renderItems()
    {
        $sections = [];
        foreach ($this->items as $i => $item) {
            if (!empty($item['values'])) {
                $sectionOptions = ['class' => 'section'];
                if (isset($item['checked']))
                    Html::addCssClass($sectionOptions, 'active');

                $section = $this->renderItem($item);
                $sections[] = Html::tag('li', $section, ['class' => $sectionOptions]);
            }
        }

        return implode("\n", $sections);
    }

    public function renderItem($item)
    {
        $result = Html::tag('div', $item['name'], ['class' => 'section--name']);
        $values = [];
        foreach ($item['values'] as $variant)
        {
            $values[] = $this->renderValue($variant);
        }

        $result .= Html::tag('div', Html::tag('ul', implode("\n", $values)), ['class' => 'wrapper']);

        return $result;
    }

    protected function renderValue($value)
    {
        $options = [];
        if (isset($value['checked']) && $value['checked'])
            $options['class'] = 'active';

        return Html::tag('li', Html::a($value['name'].' <span class="small">'.$value['count'].'</span>', $value['link']), $options);
    }
}