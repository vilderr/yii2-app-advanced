<?php

use yii\helpers\Html;
use common\collects\catalog\category\CategoryCollection as Collection;
use kartik\select2\Select2;

/**
 * @var $form       \backend\widgets\ActiveForm
 * @var $collection Collection
 */
?>
<div class="form-group">
    <label class="control-label"><?= $collection->properties->getAttributeLabel('values') ?></label>
    <?= Html::hiddenInput($collection->properties->formName() . '[values]'); ?>
    <?= Select2::widget([
        'name'              => $collection->properties->formName() . '[values]',
        'value'             => $collection->properties->values,
        'data'              => $collection->properties->variants,
        'maintainOrder'     => true,
        'toggleAllSettings' => [
            'selectLabel'     => '<i class="glyphicon glyphicon-ok-circle"></i> ' . Yii::t('app', 'Select all'),
            'unselectLabel'   => '<i class="glyphicon glyphicon-remove-circle"></i> ' . Yii::t('app', 'Выбрать все'),
            'selectOptions'   => ['class' => 'text-success'],
            'unselectOptions' => ['class' => 'text-danger'],
        ],
        'options'           => [
            'placeholder' => '...',
            'multiple'    => true,
        ],
        'pluginOptions'     => [
            'tags' => true,
        ],
    ]); ?>
</div>
