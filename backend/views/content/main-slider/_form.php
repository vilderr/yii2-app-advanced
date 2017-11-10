<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\collects\content\MainSliderCollection as Collection;
use kartik\file\FileInput;

/**
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

if ($collection->slide && $collection->slide->picture) {
    $pluginOptions['initialPreview'][] = $collection->slide->picture->getThumbFileUrl('file', 'admin');
    $pluginOptions['initialPreviewConfig'][] = [
        'caption'  => $collection->slide->picture->file,
        'showDrag' => false,
        'url'      => Url::toRoute(['delete-picture', 'id' => $collection->slide->id]),
    ];
}
?>
<div class="panel panel-default">
    <?php $form = ActiveForm::begin(); ?>
    <div class="panel-body">
        <?= $form->field($collection, 'text')->textInput(); ?>
        <?= $form->field($collection, 'url')->textInput(); ?>
        <?= $form->field($collection->picture, 'picture')->widget(FileInput::class, [
            'options'       => ['multiple' => false, 'accept' => 'image/*'],
            'pluginOptions' => $pluginOptions,
        ]); ?>
        <?= $form->field($collection, 'sort')->textInput(); ?>
        <?= $form->field($collection, 'status')->checkbox(); ?>
    </div>
    <div class="panel-footer">
        <div class="button-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'action', 'value' => 'save']) ?>
            <?= Html::submitButton(Yii::t('app', 'Apply'), ['class' => 'btn btn-default', 'name' => 'action', 'value' => 'apply']) ?>
            <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-default pull-right']); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
