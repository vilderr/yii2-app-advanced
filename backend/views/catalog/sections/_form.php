<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use backend\widgets\ActiveForm;
use common\collects\catalog\category\CategoryCollection as Collection;

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
                'id' => 'category-common-tab',
            ],
        ],
        [
            'label'   => 'Описание и фото',
            'content' => $this->render('parts/description', ['form' => $form, 'collection' => $collection]),
            'options' => [
                'id' => 'category-description-tab',
            ],
        ],
        [
            'label'   => Yii::t('app', 'Properties'),
            'content' => $this->render('parts/properties', ['form' => $form, 'collection' => $collection]),
            'options' => [
                'id' => 'category-properties-tab',
            ],
        ],
        [
            'label'   => 'SEO',
            'content' => $this->render('parts/meta', [
                'form'       => $form,
                'collection' => $collection,
            ]),
            'options' => [
                'id' => 'category-seo-tab',
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
        ['index', 'id' => $collection->parent ? $collection->parent->id : 0],
        ['class' => 'btn btn-default pull-right']
    ); ?>
</div>
<?php ActiveForm::end(); ?>

