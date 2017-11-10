<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\dynagrid\DynaGrid;
use kartik\widgets\DatePicker;
use kartik\field\FieldRange;
use backend\collects\catalog\ProductSearchCollection;
use common\models\catalog\product\Product;
use common\models\catalog\category\Category;
use common\models\helpers\ProductHelper;

/**
 * @var $this             yii\web\View
 * @var $searchCollection ProductSearchCollection
 * @var $dataProvider     yii\data\ActiveDataProvider
 * @var $category Category
 */

$this->title = ($category ? $category->name : 'Верхний уровень') . ': товары';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => Url::to(['/catalog']),
];
if ($category) {
    $this->params['breadcrumbs'][] = [
        'label' => 'Товары',
        'url'   => ['index'],
    ];
    foreach ($category->parents as $chain) {
        $this->params['breadcrumbs'][] = [
            'label' => $chain->name,
            'url'   => ['index', 'category_id' => $chain->id],
        ];
    }

    $this->params['breadcrumbs'][] = $category->name;

} else {
    $this->params['breadcrumbs'][] = 'Товары';
}
?>
<div class="catalog-product-index">
    <?= DynaGrid::widget([
        'columns'            => [
            [
                'attribute' => 'id',
                'order'     => DynaGrid::ORDER_FIX_LEFT,
                'vAlign'    => 'middle',
                'options'   => [
                    'width' => '90px',
                ],
            ],
            [
                'attribute' => 'name',
                'value'     => function (Product $product) {
                    return Html::a($product->name, ['update', 'id' => $product->id]);
                },
                'format'    => 'raw',
                'vAlign'    => 'middle',
            ],
            [
                'filter'        => DatePicker::widget([
                    'model'         => $searchCollection,
                    'attribute'     => 'created_date_from',
                    'attribute2'    => 'created_date_to',
                    'type'          => DatePicker::TYPE_RANGE,
                    'separator'     => '-',
                    'pluginOptions' => ['format' => 'yyyy-mm-dd'],
                ]),
                'attribute'     => 'created_at',
                'format'        => 'datetime',
                'filterOptions' => [
                    'style' => 'max-width: 180px',
                ],
                'vAlign'        => 'middle',
            ],
            [
                'filter'        => DatePicker::widget([
                    'model'         => $searchCollection,
                    'attribute'     => 'updated_date_from',
                    'attribute2'    => 'updated_date_to',
                    'type'          => DatePicker::TYPE_RANGE,
                    'separator'     => '-',
                    'pluginOptions' => ['format' => 'yyyy-mm-dd'],
                ]),
                'attribute'     => 'updated_at',
                'format'        => 'datetime',
                'filterOptions' => [
                    'style' => 'max-width: 180px',
                ],
                'vAlign'        => 'middle',
            ],
            [
                'filter'        => FieldRange::widget([
                    'model'      => $searchCollection,
                    'attribute1' => 'price_from',
                    'attribute2' => 'price_to',
                    'separator'  => '-',
                    'template'   => '{widget}{error}',
                    'options'    => [
                        'class' => 'filter-widget',
                    ],
                ]),
                'attribute'     => 'price',
                'format'        => 'raw',
                'filterOptions' => [
                    'style' => 'max-width: 180px',
                ],
                'vAlign'        => 'middle',
            ],
            [
                'filter'        => FieldRange::widget([
                    'model'      => $searchCollection,
                    'attribute1' => 'old_price_from',
                    'attribute2' => 'old_price_to',
                    'separator'  => '-',
                    'template'   => '{widget}{error}',
                    'options'    => [
                        'class' => 'filter-widget',
                    ],
                ]),
                'attribute'     => 'old_price',
                'format'        => 'raw',
                'filterOptions' => [
                    'style' => 'max-width: 180px',
                ],
                'vAlign'        => 'middle',
                'visible' => false
            ],
            [
                'filter'        => FieldRange::widget([
                    'model'      => $searchCollection,
                    'attribute1' => 'discount_from',
                    'attribute2' => 'discount_to',
                    'separator'  => '-',
                    'template'   => '{widget}{error}',
                    'options'    => [
                        'class' => 'filter-widget',
                    ],
                ]),
                'attribute'     => 'discount',
                'format'        => 'raw',
                'filterOptions' => [
                    'style' => 'max-width: 180px',
                ],
                'vAlign'        => 'middle',
                'visible' => false
            ],
            [
                'attribute' => 'sort',
                'vAlign'    => 'middle',
                'visible'   => false,
            ],
            [
                'attribute' => 'status',
                'filter'    => ProductHelper::statusList(),
                'value'     => function (Product $product) {
                    return ProductHelper::statusLabel($product->status);
                },
                'format'    => 'raw',
                'vAlign'    => 'middle',
            ],
            [
                'class'          => 'kartik\grid\ActionColumn',
                'template'       => '<div class="btn-group">{update}{delete}</div>',
                'buttons'        => [
                    'update' => function ($url, Product $product) {
                        return Html::a('<i class="mdi mdi-pencil"></i>', ['update', 'id' => $product->id], ['class' => 'btn btn-primary btn-sm', 'title' => Yii::t('app', 'Edit')]);
                    },
                    'delete' => function ($url, Product $product) {
                        return Html::a('<i class="mdi mdi-delete-empty"></i>', ['delete', 'id' => $product->id, 'category_id' => $product->category_id], ['class' => 'btn btn-danger btn-sm', 'data-method' => 'post', 'title' => Yii::t('app', 'Delete')]);
                    },
                ],
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'options'        => [
                    'width' => '90px',
                ],
                'order'          => DynaGrid::ORDER_FIX_RIGHT,
            ],
        ],
        'theme'              => 'panel-settings',
        'allowSortSetting'   => false,
        'allowThemeSetting'  => false,
        'allowFilterSetting' => false,
        'gridOptions'        => [
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchCollection,
            'hover'        => true,
            'panel'        => [
                'heading' => ($category ? $category->name : 'Верхний уровень') . ': список товаров',
                'before'  => Html::a('Добавить товар', ['create', 'category_id' => $category ? $category->id : null], ['class' => 'btn btn-primary']) . ' '
                    . Html::a('Категории', ['/catalog/sections/index', 'id' => $category ? $category->id : null], ['class' => 'btn btn-default btn-flat']),
                'after'   => false,
            ],
        ],
        'options'            => [
            'id' => 'products-grid',
        ],
    ]); ?>
</div>
