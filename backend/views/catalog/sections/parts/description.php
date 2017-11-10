<?php

use yii\helpers\Url;
use common\collects\catalog\category\CategoryCollection as Collection;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use kartik\file\FileInput;

/**
 * @var $form       \backend\widgets\ActiveForm
 * @var $collection Collection
 */

$pluginOptions = [
    'initialPreviewAsData' => true,
    'overwriteInitial'     => false,
    'showRemove'           => false,
    'showUpload'           => false,
    'showClose'            => false,
    'previewFileType'      => 'image',
];
if ($collection->category && $collection->category->picture) {
    $pluginOptions['initialPreview'][] = $collection->category->picture->getThumbFileUrl('file', 'admin');
    $pluginOptions['initialPreviewConfig'][] = [
        'caption'  => $collection->category->picture->file,
        'showDrag' => false,
        'url'      => Url::toRoute(['delete-picture', 'id' => $collection->category->id]),
    ];
}
?>
<?= $form->field($collection->picture, 'picture')->widget(FileInput::class, [
    'options'       => ['multiple' => false, 'accept' => 'image/*'],
    'pluginOptions' => $pluginOptions,
]); ?>
<?= $form->field($collection, 'description')->widget(CKEditor::class, [
    'editorOptions' => ElFinder::ckeditorOptions('elfinder', []),
]) ?>
