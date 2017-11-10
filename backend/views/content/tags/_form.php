<?php
use yii\helpers\Html;
use common\collects\TagCollection as Collection;
use backend\widgets\ActiveForm;

/**
 * @var $collection Collection
 */
?>
<div class="panel panel-default">
    <?php $form = ActiveForm::begin(); ?>
    <div class="panel-body">
        <?= $form->field($collection, 'name')->textInput(['maxlength' => true, 'id' => 'tag-name']); ?>
        <?= $form->field($collection, 'slug', [
            'makeSlug' => "#tag-name",
        ]); ?>
    </div>
    <div class="panel-footer">
        <div class="button-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'action', 'value' => 'save']) ?>
            <?= Html::submitButton(Yii::t('app', 'Apply'), ['class' => 'btn btn-default', 'name' => 'action', 'value' => 'apply']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
