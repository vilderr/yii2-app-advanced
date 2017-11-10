<?php
use yii\helpers\Html;
use kartik\select2\Select2;
use common\collects\catalog\ProductCollection as Collection;
use common\repo\TagRepository;

/**
 * @var $form       \yii\widgets\ActiveForm
 * @var $collection Collection
 */
?>
<div class="form-group">
    <?= Html::hiddenInput($collection->tags->formName() . '[values]'); ?>
    <label class="control-label"><?= $collection->tags->getAttributeLabel('values') ?></label>
    <?= Select2::widget([
        'name'          => $collection->tags->formName() . '[values]',
        'value'         => $collection->tags->values,
        'data'          => TagRepository::getDropDownList(),
        'maintainOrder' => true,
        'toggleAllSettings' => [
            'selectLabel' => '<i class="glyphicon glyphicon-ok-circle"></i> Выбрать все',
            'unselectLabel' => '<i class="glyphicon glyphicon-remove-circle"></i> Удалить все',
            'selectOptions' => ['class' => 'text-success'],
            'unselectOptions' => ['class' => 'text-danger'],
        ],
        'options'       => [
            'placeholder' => '...',
            'multiple' => true,
        ],
        'pluginOptions' => [
            'tags'               => true,
        ],
    ]);
    ?>
</div>