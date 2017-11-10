<?php

use yii\widgets\ActiveForm;
use common\collects\catalog\distribution\DistributionProfilePartCollection as Collection;

/**
 * @var $form       ActiveForm
 * @var $collection Collection
 */
?>
<?= $form->field($collection, 'name')->textInput(['maxlength' => true]); ?>
<?= $form->field($collection, 'sort')->textInput(); ?>
<?= $form->field($collection, 'status')->checkbox(); ?>
