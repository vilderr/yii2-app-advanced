<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\dynagrid\DynaGrid;
use common\models\helpers\ImportProfileHelper;
use common\models\catalog\import\ImportProfile as Profile;
use common\collects\catalog\import\ImportProfileSearchCollection as SearchCollection;

/**
 * @var $this         yii\web\View
 * @var $collection   SearchCollection
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$this->title = 'Профили импорта';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => Url::to(['/catalog']),
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="import-profile-index">
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
                'value'     => function (Profile $profile) {
                    return Html::a($profile->name, ['run', 'id' => $profile->id]);
                },
                'format'    => 'raw',
                'vAlign'    => 'middle',
            ],
            [
                'attribute' => 'sections_url',
                'vAlign'    => 'middle',
                'visible'   => false,
            ],
            [
                'attribute' => 'products_url',
                'vAlign'    => 'middle',
                'visible'   => false,
            ],
            [
                'attribute' => 'api_key',
                'vAlign'    => 'middle',
                'visible'   => false,
            ],
            [
                'attribute' => 'sort',
                'vAlign'    => 'middle'
            ],
            [
                'attribute' => 'status',
                'filter'    => ImportProfileHelper::statusList(),
                'value'     => function (Profile $profile) {
                    return ImportProfileHelper::statusLabel($profile->status);
                },
                'format'    => 'raw',
                'vAlign'    => 'middle',
            ],
            [
                'class'          => 'kartik\grid\ActionColumn',
                'template'       => '<div class="btn-group">{update}{delete}</div>',
                'buttons'        => [
                    'update' => function ($url, Profile $profile) {
                        return Html::a('<i class="mdi mdi-pencil"></i>', ['update', 'id' => $profile->id], ['class' => 'btn btn-primary btn-sm', 'title' => Yii::t('app', 'Edit')]);
                    },
                    'delete' => function ($url, Profile $profile) {
                        return Html::a('<i class="mdi mdi-delete-empty"></i>', ['delete', 'id' => $profile->id], ['class' => 'btn btn-danger btn-sm', 'data-method' => 'post', 'title' => Yii::t('app', 'Delete')]);
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
        'allowThemeSetting'  => false,
        'allowFilterSetting' => false,
        'gridOptions'        => [
            'dataProvider' => $dataProvider,
            'filterModel'  => $collection,
            'hover'        => true,
            'panel'        => [
                'heading' => 'Cписок профилей импорта',
                'before'  => Html::a('Добавить профиль', ['create'], ['class' => 'btn btn-primary']),
                'after'   => false,
            ],
        ],
        'options'            => [
            'id' => 'products-grid',
        ],
    ]); ?>
</div>
