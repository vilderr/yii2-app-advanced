<?php

use common\collects\catalog\category\CategoryCollection as Collection;

/**
 * @var $form       \backend\widgets\ActiveForm
 * @var $collection Collection
 */
?>
<?= $form->field($collection, 'name')->textInput(['maxlength' => true, 'id' => 'category-name']) ?>
<?= $form->field($collection, 'slug', [
    'makeSlug' => "#category-name"
]); ?>
<?= $form->field($collection, 'xml_id')->textInput(); ?>
<?= $form->field($collection, 'sort')->textInput(); ?>
<?= $form->field($collection, 'status')->checkbox(); ?>