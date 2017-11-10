<?php
use common\collects\catalog\category\CategoryCollection as Collection;

/**
 * @var $form       \backend\widgets\ActiveForm
 * @var $collection Collection
 */
?>
<div>
    <div class="h4">Настройки раздела</div>
    <hr>
    <?= $form->field($collection->meta, 'title')->textInput(['maxlength' => true]); ?>
    <?= $form->field($collection->meta, 'description')->textInput(); ?>
    <?= $form->field($collection->meta, 'keywords')->textInput(); ?>
    <br>
    <div class="h4">Настройки элементов</div>
    <hr>
    <?= $form->field($collection->meta, 'product_title')->textInput(['maxlength' => true]); ?>
    <?= $form->field($collection->meta, 'product_description')->textInput(); ?>
    <?= $form->field($collection->meta, 'product_keywords')->textInput(); ?>
</div>
