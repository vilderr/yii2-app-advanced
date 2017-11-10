<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use common\collects\catalog\property\PropertyCollection as Collection;

/**
 * @var $this       \yii\web\View
 * @var $collection Collection
 */
?>
<div class="panel panel-default">
    <? $form = ActiveForm::begin(); ?>
    <div class="panel-body">
        <?= $form->field($collection, 'name')->textInput(['maxlength' => true, 'id' => 'property-name-input']); ?>
        <?= $form->field($collection, 'slug', [
            'makeSlug' => "#property-name-input",
            'replace'  => ['space' => '_', 'other' => '_'],
        ]); ?>
        <?= $form->field($collection, 'xml_id')->textInput(); ?>
        <?= $form->field($collection, 'sort')->textInput(); ?>
        <?= $form->field($collection, 'status')->checkbox(); ?>
        <?= $form->field($collection, 'filtrable')->checkbox(); ?>
        <?= $form->field($collection, 'sef')->checkbox(); ?>
    </div>
    <div class="panel-footer">
        <?= Html::submitButton(Yii::t('app', 'Save'), [
            'class' => 'btn btn-primary',
            'name'  => 'action',
            'value' => 'save',
        ]); ?>
        <?= Html::submitButton(Yii::t('app', 'Apply'), [
            'class' => 'btn btn-default btn-flat',
            'name'  => 'action',
            'value' => 'apply',
        ]); ?>
        <?= Html::a(
            Yii::t('app', 'Cancel'),
            ['index'],
            ['class' => 'btn btn-default pull-right']
        ); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

