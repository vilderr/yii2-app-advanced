<?php

namespace frontend\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\catalog\product\Product;
use frontend\widgets\assets\ProductTopSliderAsset;

/**
 * Class ProductTopSlider
 * @package frontend\widgets
 */
class ProductTopSlider extends Widget
{
    public $options = [];
    public $listOptions = [];
    public $itemOptions = [];
    public $items = [];

    public function run()
    {
        if (!empty($this->items)) {
            ProductTopSliderAsset::register($this->view);

            $options = $this->options;
            $title = ArrayHelper::remove($options, 'title', null);
            $listOptions = $this->listOptions;
            $tag = ArrayHelper::remove($options, 'tag', 'div');
            $listTag = ArrayHelper::remove($listOptions, 'tag', 'div');

            $content = Html::beginTag($tag, $options) . "\n";
            $content .= $title ? Html::tag('h3', $title, ['class' => 'title slider-title']) . "\n" : "";
            $content .= Html::tag($listTag, $this->renderItems($this->items), $listOptions) . "\n";
            $content .= Html::endTag($tag) . "\n";

            echo $content;

            $class = ArrayHelper::remove($listOptions, 'class');
            $md5 = 'topSlider'.md5($class);

            $js = <<<EOT
var $md5 = $(".$class").bxSlider();

$(window).on('resize.$md5', function() {
    
    if ($(window).width() > 992) {
        $md5.reloadSlider($.extend(!0, topSliderOpt, {minSlides: 6, maxSlides: 6, touchEnabled: false}));
    } else if ($(window).width() <= 992 && $(window).width() > 768) {
        $md5.reloadSlider($.extend(!0, topSliderOpt, {minSlides: 4, maxSlides: 4, touchEnabled: false}));
    } else if ($(window).width() <= 768 && $(window).width() > 576) {
        $md5.reloadSlider($.extend(!0, topSliderOpt, {minSlides: 3, maxSlides: 3, touchEnabled: true}));
    } else {
        $md5.reloadSlider($.extend(!0, topSliderOpt, {minSlides: 2, maxSlides: 2, touchEnabled: true}));
    }
}).trigger('resize.$md5');
EOT;
            $this->view->registerJs($js, $this->view::POS_END);
        }
    }

    private function renderItems($items)
    {
        $lines = [];
        foreach ($items as $item) {
            $lines[] = $this->renderItem($item);
        }

        return implode("\n", $lines);
    }

    protected function renderItem(Product $product)
    {
        return $this->render('_product', [
            'product' => $product,
        ]);
    }
}