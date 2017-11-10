<?php
use backend\widgets\ActiveForm;
use common\collects\content\PageCollection as Collection;

/**
 * @var $form       ActiveForm
 * @var $collection Collection
 */
?>
<?= $form->field($collection->meta, 'title')->textInput(); ?>
<?= $form->field($collection->meta, 'description')->textarea(['rows' => 2]); ?>
<?= $form->field($collection->meta, 'keywords')->textInput(); ?>
