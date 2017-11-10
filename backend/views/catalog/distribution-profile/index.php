<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\catalog\distribution\DistributionProfile as Profile;
use common\collects\catalog\distribution\DistributionProfileSearchCollection as SearchCollection;
use common\models\helpers\ModelHelper;
use kartik\dynagrid\DynaGrid;

/**
 * @var $this         yii\web\View
 * @var $collection   SearchCollection
 * @var $dataProvider \yii\data\ActiveDataProvider
 *
 */

$this->title = 'Профили распределения';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => Url::to(['/catalog']),
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="distribution-profiles-index">
    <?= DynaGrid::widget([
        'columns'            => [
            [
                'attribute' => 'id',
                'order'     => DynaGrid::ORDER_FIX_LEFT,
            ],
            [
                'attribute' => 'name',
                'value'     => function (Profile $profile) {
                    return Html::a(Html::encode($profile->name), ['run', 'profile_id' => $profile->id]);
                },
                'format'    => 'raw',
            ],
            [
                'attribute' => 'description',
                'value'     => function (Profile $profile) {
                    return Html::encode($profile->description);
                },
                'format'    => 'raw',
            ],
            'sort',
            [
                'attribute' => 'status',
                'filter'    => ModelHelper::statusList(),
                'value'     => function (Profile $profile) {
                    return ModelHelper::statusLabel($profile->status);
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
                'heading' => 'Список профилей',
                'before'  => Html::a('Добавить профиль', ['create'], ['class' => 'btn btn-primary']),
                'after'   => false,
            ],
        ],
        'options'            => [
            'id' => 'properties-grid',
        ],
    ]); ?>
</div>
