<?php

use \kartik\dynagrid\DynaGrid;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this         \yii\web\View;
 * @var $item         \backend\models\BackendMenu
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = ($item ? $item->name : 'Верхний уровень') . ': пункты меню';
if ($item) {
    $this->params['breadcrumbs'][] = [
        'label' => 'Меню',
        'url'   => Url::toRoute(['index']),
    ];

    foreach ($item->parents as $chain) {
        $this->params['breadcrumbs'][] = [
            'label' => $chain->name,
            'url'   => ['index', 'id' => $chain->id],
        ];
    }

    $this->params['breadcrumbs'][] = $item->name;

} else {
    $this->params['breadcrumbs'][] = 'Меню';
}
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
        'route',
        [
            'attribute' => 'icon',
            'value'     => function ($model) {
                return Html::tag('i', '', ['class' => 'mdi mdi-' . $model->icon]);
            },
            'format'    => 'raw',
        ],
        'sort',
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
            'heading' => 'Пункты меню: ' . ($item ? $item->name : 'Верхний уровень'),
            'before'  => Html::a('Добавить пункт меню', ['create', 'parent_id' => $item ? $item->id : 0], ['class' => 'btn btn-primary']),
            'after'   => false,
        ],
    ],
    'options'           => [
        'id' => 'users-grid',
    ],
]); ?>