<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use common\collects\TagSearchCollection as Collection;
use common\models\Tag;

/**
 * @var $this \yii\web\View
 * @var $collection Collection
 * @var $dataProvider ActiveDataProvider
 */

$this->title = 'Теги';
$this->params['breadcrumbs'][] = [
    'label' => 'Контент',
    'url'   => ['/content'],
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tags-index">
    <?= DynaGrid::widget([
        'columns'           => [
            [
                'attribute' => 'id',
                'order'     => DynaGrid::ORDER_FIX_LEFT,
            ],
            [
                'attribute' => 'name',
                'value'     => function (Tag $tag) {
                    return Html::a(Html::encode($tag->name), ['update', 'id' => $tag->id]);
                },
                'format'    => 'raw',
            ],
            'slug',
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
        'theme'             => 'panel-settings',
        'allowSortSetting'  => false,
        'allowThemeSetting' => false,
        'allowFilterSetting' => false,
        'gridOptions'       => [
            'dataProvider' => $dataProvider,
            'filterModel'  => $collection,
            'hover'        => true,
            'panel'        => [
                'heading' => 'Список тегов',
                'before'  => Html::a('Добавить тег', ['create'], ['class' => 'btn btn-primary']),
                'after'   => false,
            ],
        ],
        'options'           => [
            'id' => 'tags-grid',
        ],
    ]); ?>
</div>
