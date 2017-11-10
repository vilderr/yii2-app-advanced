<?php

use backend\collects\catalog\ProductCollection as Collection;
use common\models\catalog\category\Category;

/**
 * @var $this       \yii\web\View
 * @var $collection Collection
 * @var $category   Category
 */

$this->title = ($category ? $category->name : 'Верхний уровень') . ': новый товар';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => ['/catalog'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Товары',
    'url'   => ['index'],
];
if ($category) {
    foreach ($category->parents as $chain) {
        $this->params['breadcrumbs'][] = [
            'label' => $chain->name,
            'url'   => ['index', 'category_id' => $chain->id],
        ];
    }
    $this->params['breadcrumbs'][] = [
        'label' => $category->name,
        'url'   => ['index', 'category_id' => $category->id],
    ];
}
$this->params['breadcrumbs'][] = 'Добавление'
?>
<div class="catalog-product-create">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]); ?>
</div>
