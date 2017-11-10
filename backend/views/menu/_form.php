<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\collects\BackendMenuCollection as Collection;

/**
 * @var $this       \yii\web\View
 * @var $collection Collection;
 */
?>
<div class="panel panel-default">
    <?php $form = ActiveForm::begin(); ?>
    <div class="panel-body">
        <?= $form->field($collection, 'name')->textInput(['maxlength' => true]); ?>
        <?= $form->field($collection, 'route')->textInput(); ?>
        <?= $form->field($collection, 'icon')->textInput(); ?>
        <?= $form->field($collection, 'sort')->textInput(); ?>
    </div>
    <div class="panel-footer">
        <div class="button-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'action', 'value' => 'save']) ?>
            <?= Html::submitButton(Yii::t('app', 'Apply'), ['class' => 'btn btn-default', 'name' => 'action', 'value' => 'apply']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
