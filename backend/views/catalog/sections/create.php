<?php

use common\models\catalog\category\Category;
use common\collects\catalog\category\CategoryCollection as Collection;

/**
 * @var $this       \yii\web\View
 * @var $collection Collection
 * @var $parent     Category
 */

$this->title = $parent->name . ': новая категория';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => ['/catalog'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Категории',
    'url'   => ['index'],
];

foreach ($parent->parents as $chain) {
    $this->params['breadcrumbs'][] = [
        'label' => $chain->name,
        'url'   => ['index', 'id' => $chain->id],
    ];
}

$this->params['breadcrumbs'][] = [
    'label' => $parent->name,
    'url'   => ['index', 'id' => $parent->id],
];

$this->params['breadcrumbs'][] = 'Добавление';
?>
<div class="catalog-category-create">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]); ?>
</div>
