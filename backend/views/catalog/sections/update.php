<?php

use common\models\catalog\category\Category;
use common\collects\catalog\category\CategoryCollection as Collection;

/**
 * @var $this         \yii\web\View
 * @var $collection   Collection
 * @var $category     Category
 */

$this->title = $category->name . ': редактирование';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => ['/catalog'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Категории',
    'url'   => ['index'],
];

foreach ($category->parents as $chain) {
    $this->params['breadcrumbs'][] = [
        'label' => $chain->name,
        'url'   => ['index', 'id' => $chain->id],
    ];
}

$this->params['breadcrumbs'][] = $category->name;
//echo '<pre>'; print_r($collection->collections); echo '</pre>';
?>
<div class="catalog-category-create">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]); ?>
</div>
