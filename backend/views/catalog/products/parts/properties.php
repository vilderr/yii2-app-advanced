<?php
use yii\helpers\Html;
use common\collects\catalog\ProductCollection as Collection;
use kartik\widgets\Select2;

/**
 * @var $form       \yii\widgets\ActiveForm
 * @var $collection Collection
 */
?>
<?php foreach ($collection->values as $pid => $value): ?>
    <?= Html::hiddenInput($value->formName() . '[' . $pid . '][values]'); ?>
    <div class="form-group">
        <label class="control-label"><?= $value->getAttributeLabel('values') ?></label>
        <?= Select2::widget([
            'name'          => $value->formName() . '[' . $pid . '][values]',
            'value'         => $value->values,
            'data'          => $value->variants(),
            'maintainOrder' => true,
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
<?php endforeach; ?>
