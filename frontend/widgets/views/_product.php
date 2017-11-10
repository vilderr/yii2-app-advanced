<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\catalog\product\Product;

/**
 * @var $product Product
 */

$url = Url::toRoute(['/catalog/product', 'id' => $product->id]);
$discount = $product->discount ?? false;
$price = $product->price;
$oldprice = $product->old_price ?? false;
$brand = $product->values[0] ? $product->values[0]->name : false;
?>
<div class="product-thumb">
    <div class="thumb-outer">
        <div class="image">
            <a href="<?= Html::encode($url) ?>"
               style="background-image: url(<?= $product->picture->getThumbFileUrl('file', 'list_picture'); ?>)">
                <?php if ($discount): ?>
                    <div class="label discount"><?= $discount; ?>%</div>
                <? endif; ?>
            </a>
        </div>
        <div class="title">
            <a href="<?= Html::encode($url) ?>"
               title="<?= $product->name; ?>"><?= \yii\helpers\StringHelper::truncate($product->name, 40, '...') ?></a>
            <?php if ($brand): ?>
                <div class="brand">
                    <span><?= $brand; ?></span>
                </div>
            <? endif; ?>
        </div>
        <div class="price">
            <span class="current"><?= $price; ?>р.</span>
            <? if ($oldprice): ?>
                <span class="old"><?= $oldprice; ?>р.</span>
            <? endif; ?>
        </div>
        <div class="links">
            <a href="#">В избранное <span class="mdi mdi-heart-outline"></span></a><br>
        </div>
    </div>
</div>
