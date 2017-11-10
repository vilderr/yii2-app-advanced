<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use backend\widgets\ActiveForm;
use common\collects\catalog\ProductCollection as Collection;

/**
 * @var $this       \yii\web\View
 * @var $collection Collection
 */
?>
<? $form = ActiveForm::begin(); ?>
<?= Tabs::widget([
    'id'    => 'category-form-tabs',
    'items' => [
        [
            'label'   => 'Основные настройки',
            'content' => $this->render('parts/common', ['form' => $form, 'collection' => $collection]),
            'active'  => true,
            'options' => [
                'id' => 'product-common-tab',
            ],
        ],
        [
            'label'   => 'Описание и фото',
            'content' => $this->render('parts/description', ['form' => $form, 'collection' => $collection]),
            'options' => [
                'id' => 'product-description-tab',
            ],
        ],
        [
            'label'   => 'Свойства',
            'content' => $this->render('parts/properties', ['form' => $form, 'collection' => $collection]),
            'options' => [
                'id' => 'product-properties-tab',
            ],
        ],
        [
            'label'   => 'Цены и скидки',
            'content' => $this->render('parts/prices', ['form' => $form, 'collection' => $collection]),
            'options' => [
                'id' => 'product-prices-tab',
            ],
        ],
        [
            'label'   => 'Теги',
            'content' => $this->render('parts/tags', ['form' => $form, 'collection' => $collection]),
            'options' => [
                'id' => 'tags',
            ],
        ],
        [
            'label'   => 'Дополнительно',
            'content' => $this->render('parts/advanced', ['form' => $form, 'collection' => $collection]),
            'options' => [
                'id' => 'product-advanced-tab',
            ],
        ],
    ],
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
        ['index', 'category_id' => $collection->category_id ? $collection->category_id : null],
        ['class' => 'btn btn-default pull-right']
    ); ?>
</div>
<?php ActiveForm::end(); ?>

