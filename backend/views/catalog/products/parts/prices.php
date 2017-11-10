<?php
use common\collects\catalog\ProductCollection as Collection;

/**
 * @var $form       \yii\widgets\ActiveForm
 * @var $collection Collection
 */
?>
<?= $form->field($collection, 'price')->textInput(); ?>
<?= $form->field($collection, 'old_price')->textInput(); ?>
<?= $form->field($collection, 'discount')->textInput(); ?>
