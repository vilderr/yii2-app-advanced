<?php
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\catalog\product\Product;

/**
 * @var $this       View
 * @var $product    Product
 * @var $properties array
 */

$this->title = $product->name;

$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => Url::toRoute(['index']),
];

foreach ($product->category->chainTree as $chunk) {
    $this->params['breadcrumbs'][] = [
        'label' => $chunk->name,
        'url'   => Url::toRoute(['category', 'id' => $chunk->id]),
    ];
}

$this->params['breadcrumbs'][] = [
    'label' => $product->category->name,
    'url'   => Url::toRoute(['category', 'id' => $product->category->id]),
];

$this->params['breadcrumbs'][] = $product->name;

$oldPrice = $product->old_price ?? false;
$discount = $product->discount ?? false;
?>
<div id="product-detail">
    <div class="row">
        <div class="col-24 col-md-11">
            <div class="product--image info--section">
                <?= Html::a(Html::img($product->picture->getThumbFileUrl('file', 'detail_picture'), ['class' => 'img-fluid']), ['goto', 'id' => $product->id]); ?>
            </div>
        </div>
        <div class="col-24 col-md-12 ml-md-auto">
            <div class="info-wrapper">
                <div class="product--header info--section">
                    <h1><?= $this->title; ?></h1>
                </div>
                <div class="product--price info--section">
                    <div class="row align-items-center">
                        <div class="col">
                            <label>Цена</label>
                            <div class="price current"><?= $product->price; ?> р.</div>
                        </div>
                        <? if ($oldPrice): ?>
                            <div class="col">
                                <label>Старая цена</label>
                                <div class="price old"><?= $product->old_price; ?> р.</div>
                            </div>
                        <? endif; ?>
                        <? if ($discount): ?>
                            <div class="col">
                                <div class="discount"><?= $product->discount; ?>%</div>
                            </div>
                        <? endif; ?>
                    </div>
                </div>
                <div class="info--section">
                    <div class="icon-text delivery-text">
                        <p>Доставка по Москве: <span class="text-success font-weight-bold">Бесплатно</span></p>
                    </div>
                    <div class="icon-text wishlist--button"><a href="#">В избранное</a> <i>(47)</i></div>
                </div>
                <div class="product--vendor-button info--section">
                    <?= Html::a('Перейти в магазин', Url::to(['goto', 'id' => $product->id]), ['class' => 'btn btn-primary btn-lg']); ?>
                </div>
                <hr>
                <div class="info--section">
                    <h3>Описание</h3>
                    <p>Мотобур ADA Ground Drill 2 - легкий и надежный мотобур для частного применения одним оператором.
                        Любые работы по бурению до 1,5 -2 метров на земельном участке.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-24 col-md-11">
            <div class="info--section">
                <h3>Полное описание <?= $product->name; ?></h3>
                <p>Мотобур ADA Ground Drill 2 - легкий и надежный мотобур для частного применения одним оператором.
                    Любые
                    работы по бурению до 1,5 -2 метров на земельном участке. Специальная конструкция рамы очень удобна
                    для
                    транспортировки и хранения. Мощность мотобура позволяет, при необходимости, использовать шнеки
                    диаметром
                    до 200 мм.</p>
            </div>
        </div>
        <div class="col-24 col-md-12 ml-md-auto">
            <? if (!empty($properties)): ?>
                <div class="info--section">
                    <h3>Характеристики</h3>
                </div>
                <table class="table table-striped table-sm">
                    <? foreach ($properties as $pid => $property): ?>
                        <tr>
                            <td><?= $property['name'] ?></td>
                            <td><?= implode(' / ', ArrayHelper::getColumn($property['values'], 'name')); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <? endif; ?>
        </div>
    </div>
</div>
