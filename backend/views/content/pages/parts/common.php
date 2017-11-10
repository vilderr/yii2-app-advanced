<?php

use backend\widgets\ActiveForm;
use common\collects\content\PageCollection as Collection;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

/**
 * @var $form       ActiveForm
 * @var $collection Collection
 */
?>
<?= $form->field($collection, 'name')->textInput([
    'maxlength' => true,
    'id'        => 'page-name',
]); ?>

<?= $form->field($collection, 'title')->textInput([
    'maxlength' => true,
]); ?>

<?= $form->field($collection, 'slug', ['makeSlug' => "#page-name"])->textInput([
    'maxlength' => true,
]); ?>

<?= $form->field($collection, 'content')->widget(CKEditor::class, [
    'editorOptions' => ElFinder::ckeditorOptions('elfinder', []),
]) ?>

<?= $form->field($collection, 'sort')->textInput([
    'maxlength' => true,
]); ?>

<?= $form->field($collection, 'status')->checkbox(); ?>
