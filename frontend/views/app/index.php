<?php
use yii\web\View;
use common\models\content\MainSlider as Slide;
use frontend\widgets\MainSlider;
use frontend\widgets\CategoryList;
use frontend\widgets\ProductTopSlider;
use common\models\catalog\category\Category;
use common\models\catalog\product\Product;

/**
 * @var $this View
 * @var $slides Slide[]
 * @var $categories Category[]
 * @var $hotProducts Product[]
 * @var $topViewedProducts Product[]
 */

$this->title = 'My Yii Application';
?>
<div class="section">
    <?= MainSlider::widget([
        'items'   => $slides,
        'options' => [
            'class' => 'section main-slider-wrap',
        ],
        'listOptions' => [
            'class' => 'main-slider',
        ],
    ]); ?>
</div>
<div class="container">
    <div class="section">
        <?= CategoryList::widget([
            'items'   => $categories,
            'options' => [
                'class' => 'category-top-list',
            ],
        ]); ?>
    </div>
    <?= ProductTopSlider::widget([
        'items'       => $hotProducts,
        'options'     => [
            'title' => 'Горячие товары',
            'class' => 'section product-slider hot-list',
        ],
        'listOptions' => [
            'class' => 'hot-products',
        ],
    ]); ?>
    <div class="section banners">
        <div class="row text-center">
            <div class="col-24 col-sm-8 banner-wrap">
                <a href="#"><img src="/static/img/banner-sm-1.jpg" class="img-fluid"/></a>
            </div>
            <div class="col-24 col-sm-8 banner-wrap">
                <a href="#"><img src="/static/img/banner-sm-2.jpg" class="img-fluid"/></a>
            </div>
            <div class="col-24 col-sm-8 banner-wrap">
                <a href="#"><img src="/static/img/banner-sm-1.jpg" class="img-fluid"/></a>
            </div>
        </div>
    </div>
    <?= ProductTopSlider::widget([
        'items'       => $topViewedProducts,
        'options'     => [
            'title' => 'Популярные товары',
            'class' => 'section product-slider top-list',
        ],
        'listOptions' => [
            'class' => 'top-viewed-products',
        ],
    ]); ?>
</div>
