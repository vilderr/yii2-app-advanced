<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use common\collects\catalog\distribution\DistributionProfilePartCollection as Collection;

/**
 * @var $collection Collection
 */
?>
<?php $form = ActiveForm::begin(); ?>
<?= Tabs::widget([
    'id'    => 'distribution-profile-part-tabs',
    'items' => [
        [
            'label'   => 'Основные настройки',
            'content' => $this->render('parts/common', ['form' => $form, 'collection' => $collection]),
            'active'  => true,
            'options' => [
                'id' => 'distribution-profile-part-common-tab',
            ],
        ],
        [
            'label'   => 'Фильтр',
            'content' => $this->render('parts/filter', ['form' => $form, 'collection' => $collection]),
            'options' => [
                'id' => 'distribution-profile-part-filter-tab',
            ],
        ],
        [
            'label'   => 'Действия',
            'content' => $this->render('parts/actions', ['form' => $form, 'collection' => $collection]),
            'options' => [
                'id' => 'distribution-profile-part-actions-tab',
            ],
        ],
    ]
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
        ['index', 'profile_id' => $collection->profile->id],
        ['class' => 'btn btn-default pull-right']
    ); ?>
</div>
<?php ActiveForm::end(); ?>

