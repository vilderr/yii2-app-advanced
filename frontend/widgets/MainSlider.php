<?php

namespace frontend\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\widgets\assets\MainSliderAsset;
use yii\base\Widget;
use common\models\content\MainSlider as Slide;

/**
 * Class MainSlider
 * @package frontend\widgets
 */
class MainSlider extends Widget
{
    public $options = [];
    public $items = [];
    public $listOptions = [];
    public $itemOptions = [];

    public function run()
    {
        if (!empty($this->items)) {
            MainSliderAsset::register($this->view);

            $options = $this->options;
            $listOptions = $this->listOptions;
            $tag = ArrayHelper::remove($options, 'tag', 'div');
            $listTag = ArrayHelper::remove($listOptions, 'tag', 'div');

            $content = Html::beginTag($tag, $options) . "\n";
            $content .= Html::tag($listTag, $this->renderItems($this->items), $listOptions) . "\n";
            $content .= Html::endTag($tag) . "\n";

            echo $content;

            $class = ArrayHelper::remove($listOptions, 'class');
            $md5 = 'mainSlider'.md5($class);

            $js = <<<EOT
var d = {
    slideMargin:0,
    slideWidth:1500,
    minSlides: 1,
    maxSlides: 1,
    nextText: '<span class="mdi mdi-chevron-right"></span>',
    prevText: '<span class="mdi mdi-chevron-left"></span>'
};
var $md5 = $(".$class").bxSlider(d);
$(window).on('resize.mainslider', function() {
    
    if ($(window).width() > 992) {
        $md5.reloadSlider($.extend(!0, d, {touchEnabled: false}));
    } else if ($(window).width() <= 992 && $(window).width() > 768) {
        $md5.reloadSlider($.extend(!0, d, {touchEnabled: false}));
    } else if ($(window).width() <= 768) {
        $md5.reloadSlider($.extend(!0, d, {touchEnabled: true}));
    }
}).trigger('resize.mainslider');
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

    protected function renderItem(Slide $slide)
    {
        return Html::tag('div', Html::a(Html::img($slide->picture->getThumbFileUrl('file', 'picture')).'<div class="text"><div class="row h-100 align-items-center"><div class="col-24"><span>'.$slide->text.'</span></div></div></div>', $slide->url));
    }

}