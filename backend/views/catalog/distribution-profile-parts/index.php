<?php

use yii\helpers\Html;
use yii\web\View;
use yii\data\ActiveDataProvider;
use common\models\catalog\distribution\DistributionProfilePart as Part;
use common\collects\catalog\distribution\DistributionProfilePartSearchCollection as SearchCollection;
use kartik\dynagrid\DynaGrid;

/**
 * @var $this         View
 * @var $collection   SearchCollection
 * @var $dataProvider ActiveDataProvider
 */

$this->title = 'Итерации: "' . $collection->profile->name . '"';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => ['/catalog'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Распределение товаров',
    'url'   => ['/catalog/distribution-profile'],
];
$this->params['breadcrumbs'][] = $collection->profile->name;
?>
<div class="distribution-profile-parts-index">
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
                'value'     => function (Part $part) {
                    return Html::a($part->name, ['update', 'id' => $part->id]);
                },
                'format'    => 'raw',
                'vAlign'    => 'middle',
            ],
            [
                'class'          => 'kartik\grid\ActionColumn',
                'template'       => '<div class="btn-group">{update}{delete}</div>',
                'buttons'        => [
                    'update' => function ($url, Part $part) {
                        return Html::a('<i class="mdi mdi-pencil"></i>', ['update', 'id' => $part->id], ['class' => 'btn btn-primary btn-sm', 'title' => Yii::t('app', 'Edit')]);
                    },
                    'delete' => function ($url, Part $part) {
                        return Html::a('<i class="mdi mdi-delete-empty"></i>', ['delete', 'id' => $part->id], ['class' => 'btn btn-danger btn-sm', 'data-method' => 'post', 'title' => Yii::t('app', 'Delete')]);
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
        'theme'              => 'panel-default',
        'allowSortSetting'   => false,
        'allowFilterSetting' => false,
        'allowThemeSetting'  => false,
        'gridOptions'        => [
            'dataProvider' => $dataProvider,
            'filterModel'  => $collection,
            'hover'        => true,
            'panel'        => [
                'heading' => $collection->profile->name . ': список итераций',
                'before'  => Html::a(
                    'Добавить итерацию',
                    ['create', 'profile_id' => $collection->profile->id],
                    ['class' => 'btn btn-primary']
                ),
                'after'   => false,
            ],
        ],
        'options'            => [
            'id' => 'distribution-profile-parts-grid',
        ],
    ]); ?>
</div>
