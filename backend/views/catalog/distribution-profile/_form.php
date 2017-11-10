<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\collects\catalog\distribution\DistributionProfileCollection as Collection;

/**
 * @var $this       \yii\web\View
 * @var $collection Collection
 */
?>
<div class="panel panel-default">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'catalog-distribution-add',
        ],
    ]);
    ?>
    <div class="panel-body">
        <?= $form->field($collection, 'name')->textInput(['maxlength' => true]); ?>
        <?= $form->field($collection, 'description')->textarea(); ?>
        <?= $form->field($collection, 'sort')->textInput(); ?>
        <?= $form->field($collection, 'status')->checkbox(); ?>
    </div>
    <div class="panel-footer">
        <? if ($collection->profile): ?>
            <?=
            Html::submitButton(
                Yii::t('app', 'Save'),
                [
                    'class' => 'btn btn-primary btn-flat',
                    'name'  => 'action',
                    'value' => 'save',
                ]
            )
            ?>
        <? endif; ?>
        <? $btnClass = $collection->profile ? 'btn-default' : 'btn-primary'; ?>
        <?= Html::submitButton(
            Yii::t('app', 'Apply'),
            [
                'class' => 'btn ' . $btnClass . ' btn-flat',
                'name'  => 'action',
                'value' => 'apply',
            ]);
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
