<?php
use yii\helpers\Url;
use kartik\file\FileInput;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use backend\collects\catalog\ProductCollection as Collection;

/**
 * @var $form       \yii\widgets\ActiveForm
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

if ($collection->product && $collection->product->picture) {
    $pluginOptions['initialPreview'][] = $collection->product->picture->getThumbFileUrl('file', 'admin');
    $pluginOptions['initialPreviewConfig'][] = [
        'caption'  => $collection->product->picture->file,
        'showDrag' => false,
        'url'      => Url::toRoute(['delete-picture', 'id' => $collection->product->id, 'picture_id' => $collection->product->picture->id]),
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
