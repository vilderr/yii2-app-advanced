<?php
use backend\collects\catalog\ProductCollection as Collection;

/**
 * @var $form       \yii\widgets\ActiveForm
 * @var $collection Collection
 */
?>
<?= $form->field($collection, 'url')->textInput(['disabled' => true]); ?>
<?= $form->field($collection, 'current_section')->textInput(['disabled' => true]); ?>
