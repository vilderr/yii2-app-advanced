<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use common\collects\catalog\property\PropertySearchCollection as Collection;
use common\models\catalog\property\Property;
use common\models\helpers\PropertyHelper;

/**
 * @var $this         \yii\web\View
 * @var $collection   Collection
 * @var $dataProvider ActiveDataProvider
 */

$this->title = 'Свойства';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => ['/catalog'],
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="properties-index">
    <?= DynaGrid::widget([
        'columns'            => [
            [
                'attribute' => 'id',
                'order'     => DynaGrid::ORDER_FIX_LEFT,
            ],
            [
                'attribute' => 'name',
                'value'     => function (Property $property) {
                    return Html::a(Html::encode($property->name), ['/catalog/property-values', 'property_id' => $property->id]);
                },
                'format'    => 'raw',
            ],
            'slug',
            [
                'attribute' => 'xml_id',
                'visible'   => false,
            ],
            'sort',
            [
                'attribute' => 'status',
                'filter'    => PropertyHelper::statusList(),
                'value'     => function (Property $property) {
                    return PropertyHelper::statusLabel($property->status);
                },
                'format'    => 'raw',
                'vAlign'    => 'middle',
            ],
            [
                'attribute' => 'filtrable',
                'filter'    => PropertyHelper::statusList(),
                'value'     => function (Property $property) {
                    return PropertyHelper::statusLabel($property->filtrable);
                },
                'format'    => 'raw',
                'vAlign'    => 'middle',
                'visible'   => false,
            ],
            [
                'attribute' => 'sef',
                'filter'    => PropertyHelper::statusList(),
                'value'     => function (Property $property) {
                    return PropertyHelper::statusLabel($property->sef);
                },
                'format'    => 'raw',
                'vAlign'    => 'middle',
                'visible'   => false,
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
        'theme'              => 'panel-settings',
        'allowSortSetting'   => false,
        'allowThemeSetting'  => false,
        'allowFilterSetting' => false,
        'gridOptions'        => [
            'dataProvider' => $dataProvider,
            'filterModel'  => $collection,
            'hover'        => true,
            'panel'        => [
                'heading' => 'Список свойств',
                'before'  => Html::a('Добавить свойство', ['create'], ['class' => 'btn btn-primary']),
                'after'   => false,
            ],
        ],
        'options'            => [
            'id' => 'properties-grid',
        ],
    ]); ?>
</div>
