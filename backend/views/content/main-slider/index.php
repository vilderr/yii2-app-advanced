<?php
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use common\models\helpers\ModelHelper;
use common\models\content\MainSlider;
use kartik\dynagrid\DynaGrid;

/**
 * @var $dataProvider ActiveDataProvider
 */
$this->title = 'Слайдер';
$this->params['breadcrumbs'][] = [
    'label' => 'Контент',
    'url'   => ['/content'],
];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= DynaGrid::widget([
    'columns'           => [
        [
            'attribute' => 'id',
            'order'     => DynaGrid::ORDER_FIX_LEFT,
        ],
        [
            'attribute' => 'text',
            'vAlign'    => 'top',
            'visible'   => false,
        ],
        [
            'attribute' => 'url',
            'vAlign'    => 'top',
        ],
        'sort',
        [
            'attribute' => 'status',
            'filter'    => ModelHelper::statusList(),
            'value'     => function (MainSlider $slide) {
                return ModelHelper::statusLabel($slide->status);
            },
            'format'    => 'raw',
            'vAlign'    => 'middle',
        ],
        [
            'class'          => 'kartik\grid\ActionColumn',
            'template'       => '<div class="btn-group">{update}{delete}</div>',
            'buttons'        => [
                'update' => function ($url, MainSlider $slide) {
                    return Html::a('<i class="mdi mdi-pencil"></i>', ['update', 'id' => $slide->id], ['class' => 'btn btn-primary btn-sm', 'title' => Yii::t('app', 'Edit')]);
                },
                'delete' => function ($url, MainSlider $slide) {
                    return Html::a('<i class="mdi mdi-delete-empty"></i>', ['delete', 'id' => $slide->id], ['class' => 'btn btn-danger btn-sm', 'data-method' => 'post', 'title' => Yii::t('app', 'Delete')]);
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
            'heading' => 'Элементы слайдера',
            'before'  => Html::a('Добавить элемент', ['create'], ['class' => 'btn btn-primary']),
            'after'   => false,
        ],
    ],
    'options'           => [
        'id' => 'mainslider-grid',
    ],
]); ?>