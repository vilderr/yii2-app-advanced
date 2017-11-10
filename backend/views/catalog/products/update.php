<?php

use common\collects\catalog\ProductCollection as Collection;

/**
 * @var $this       \yii\web\View
 * @var $collection Collection
 */

$this->title = $collection->product->name . ': редактирование';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => ['/catalog'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Товары',
    'url'   => ['index'],
];
if ($collection->product->category)
{
    foreach ($collection->product->category->parents as $chain) {
        $this->params['breadcrumbs'][] = [
            'label' => $chain->name,
            'url'   => ['index', 'category_id' => $chain->id],
        ];
    }

    $this->params['breadcrumbs'][] = [
        'label' => $collection->product->category->name,
        'url'   => ['index', 'category_id' => $collection->product->category->id],
    ];
}
$this->params['breadcrumbs'][] = $collection->product->name;

//echo '<pre>'; print_r($collection); echo '</pre>';
?>
<div class="catalog-product-edit">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]); ?>
</div>
