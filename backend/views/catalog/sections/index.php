<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\catalog\category\Category;
use \kartik\dynagrid\DynaGrid;

/**
 * @var $this         \yii\web\View
 * @var $category     Category
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$this->title = $category->name . ': категории';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => Url::to(['/catalog']),
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
?>
<?= DynaGrid::widget([
    'columns'           => [
        [
            'attribute' => 'id',
            'order'     => DynaGrid::ORDER_FIX_LEFT,
        ],
        [
            'attribute' => 'name',
            'value'     => function ($model) {
                return Html::a($model->name, Url::toRoute(['index', 'id' => $model->id]));
            },
            'format'    => 'raw',
        ],
        [
            'class'          => 'kartik\grid\ActionColumn',
            'template'       => '<div class="btn-group">{update}{delete}</div>',
            'buttons'        => [
                'update' => function ($url, $model) {
                    return Html::a('<i class="mdi mdi-pencil"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm', 'title' => Yii::t('app', 'Edit')]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<i class="mdi mdi-delete-empty"></i>', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger btn-sm', 'data-method' => 'post', 'title' => Yii::t('app', 'Delete')]);
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
    'theme'             => 'panel-default',
    'allowSortSetting'  => false,
    'allowThemeSetting' => false,
    'gridOptions'       => [
        'dataProvider' => $dataProvider,
        'hover'        => true,
        'panel'        => [
            'heading' => 'Список категорий: ' . $category->name,
            'before'  => Html::a('Добавить категорию', ['create', 'parent_id' => $category->id], ['class' => 'btn btn-primary']) . '&nbsp'
                . Html::a('Товары', ['/catalog/products/index', 'category_id' => $category->id], ['class' => 'btn btn-default btn-flat']),
            'after'   => false,
        ],
    ],
    'options'           => [
        'id' => 'catalog-category-index',
    ],
]); ?>
