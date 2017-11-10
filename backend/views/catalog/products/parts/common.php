<?php

use common\collects\catalog\ProductCollection as Collection;

/**
 * @var $collection Collection
 * @var $form \yii\widgets\ActiveForm
 */
?>
<?= $form->field($collection, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($collection, 'xml_id')->textInput(); ?>
<?= $form->field($collection, 'sort')->textInput(); ?>
<?= $form->field($collection, 'status')->checkbox(); ?>
