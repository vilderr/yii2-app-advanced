<?php
use yii\helpers\Html;
use common\models\catalog\property\PropertyValue;
use common\models\helpers\ModelHelper;
use common\collects\catalog\property\PropertyValueSearchCollection as Collection;
use kartik\dynagrid\DynaGrid;

/* @var $this yii\web\View */
/* @var $collection Collection */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Значения свойства "'.$collection->property->name.'"';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => ['/catalog'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Свойства',
    'url'   => ['/catalog/properties/index'],
];
$this->params['breadcrumbs'][] = $collection->property->name;
?>
<div class="property-value-index">
    <?= DynaGrid::widget([
        'columns'           => [
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
                'value'     => function (PropertyValue $value) {
                    return Html::a($value->name, ['update', 'id' => $value->id]);
                },
                'format'    => 'raw',
                'vAlign'    => 'middle',
            ],
            [
                'attribute' => 'slug',
                'vAlign'    => 'middle',
            ],
            [
                'attribute' => 'xml_id',
                'vAlign'    => 'middle',
                'visible'   => false,
            ],
            [
                'attribute' => 'sort',
                'vAlign'    => 'middle',
            ],
            [
                'attribute' => 'status',
                'filter'    => ModelHelper::statusList(),
                'value'     => function (PropertyValue $value) {
                    return ModelHelper::statusLabel($value->status);
                },
                'format'    => 'raw',
                'vAlign'    => 'middle',
            ],
            [
                'class'          => 'kartik\grid\ActionColumn',
                'template'       => '<div class="btn-group">{update}{delete}</div>',
                'buttons'        => [
                    'update' => function ($url, PropertyValue $value) {
                        return Html::a('<i class="mdi mdi-pencil"></i>', ['update', 'id' => $value->id], ['class' => 'btn btn-primary btn-sm', 'title' => Yii::t('app', 'Edit')]);
                    },
                    'delete' => function ($url, PropertyValue $value) {
                        return Html::a('<i class="mdi mdi-delete-empty"></i>', ['delete', 'id' => $value->id], ['class' => 'btn btn-danger btn-sm', 'data-method' => 'post', 'title' => Yii::t('app', 'Delete')]);
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
        'allowFilterSetting'  => false,
        'allowThemeSetting' => false,
        'gridOptions'       => [
            'dataProvider' => $dataProvider,
            'filterModel'  => $collection,
            'hover'        => true,
            'panel'        => [
                'heading' => $collection->property->name . ': список значений',
                'before'  => Html::a(
                    'Добавить значение',
                    ['create', 'property_id' => $collection->property->id],
                    ['class' => 'btn btn-primary']
                ),
                'after'   => false,
            ],
        ],
        'options'           => [
            'id' => 'users-grid',
        ],
    ]); ?>
</div>
