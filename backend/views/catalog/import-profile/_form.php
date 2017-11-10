<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\collects\catalog\import\ImportProfileCollection as Collection;

/**
 * @var $collection     Collection
 * @var $remoteSections array
 * @var $tab            string
 */
?>
<?php $form = ActiveForm::begin(); ?>
<?
$items = [
    [
        'label'   => 'Основные настройки',
        'content' => $this->render('parts/common', ['form' => $form, 'collection' => $collection]),
        'active'  => true,
        'options' => [
            'id' => 'common',
        ],
    ],
];
if ($collection->profile) {
    $items[] =
        [
            'label'   => 'Категории',
            'content' => $this->render('parts/categories', [
                'form'           => $form,
                'collection'     => $collection,
                'remoteSections' => $remoteSections,
            ]),
            'active'  => $tab == 'category' ? true : false,
            'options' => [
                'id' => 'category',
            ],
        ];
}
?>
<?= \yii\bootstrap\Tabs::widget([
    'id'    => 'catalog-import-profile-tabs',
    'items' => $items,
]); ?>
<div class="panel-footer">
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-primary',
        'name'  => 'action',
        'value' => 'save',
    ]); ?>
    <?= Html::submitButton(Yii::t('app', 'Apply'), [
        'class' => 'btn btn-default btn-flat',
        'name'  => 'action',
        'value' => 'apply',
    ]); ?>
    <?= Html::a(
        Yii::t('app', 'Cancel'),
        ['index'],
        ['class' => 'btn btn-default pull-right']
    ); ?>
</div>
<?php ActiveForm::end(); ?>

