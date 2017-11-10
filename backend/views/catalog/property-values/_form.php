<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use common\collects\catalog\property\PropertyValueCollection as Collection;

/**
 * @var $this       yii\web\View
 * @var $collection Collection
 * @var $form       ActiveForm
 */
?>

<div class="panel panel-default">

    <?php $form = ActiveForm::begin(); ?>
    <div class="panel-body">
        <?= $form->field($collection, 'name')->textInput(['maxlength' => true, 'id' => 'property-value-name-input']) ?>

        <?= $form->field($collection, 'slug', [
            'makeSlug' => "#property-value-name-input",
            'replace'  => ['space' => '_', 'other' => '_']
        ]); ?>

        <?= $form->field($collection, 'xml_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($collection, 'sort')->textInput() ?>

        <?= $form->field($collection, 'status')->checkbox() ?>
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
            ['index', 'property_id' => $collection->property->id],
            ['class' => 'btn btn-default pull-right']
        ); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
