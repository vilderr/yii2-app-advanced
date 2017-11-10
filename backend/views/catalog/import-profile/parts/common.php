<?php

use yii\widgets\ActiveForm;
use common\collects\catalog\import\ImportProfileCollection as Collection;

/**
 * @var $form ActiveForm
 * @var $collection Collection
 */
?>
<?= $form->field($collection, 'name')->textInput(['maxlength' => true]); ?>
<?= $form->field($collection, 'sections_url')->textInput(['maxlength' => true]); ?>
<?= $form->field($collection, 'products_url')->textInput(['maxlength' => true]); ?>
<?= $form->field($collection, 'api_key')->textInput(['maxlength' => true]); ?>
<?= $form->field($collection, 'sort')->textInput(); ?>
<?= $form->field($collection, 'status')->checkbox(); ?>
