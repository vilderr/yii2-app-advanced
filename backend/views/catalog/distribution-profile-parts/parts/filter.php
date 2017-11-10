<?php

use yii\widgets\ActiveForm;
use common\collects\catalog\distribution\DistributionProfilePartCollection as Collection;
use common\repo\CategoryRepository;
use common\models\helpers\ModelHelper;

/**
 * @var $form       ActiveForm
 * @var $collection Collection
 */
?>
<?= $form->field($collection, 'filter_name')->textInput(); ?>
<?= $form->field($collection, 'filter_category_id')->dropDownList(CategoryRepository::tree(), ['prompt' => Yii::t('app', 'Not choosed')]); ?>
<?= $form->field($collection, 'filter_status')->dropDownList(ModelHelper::statusList(), ['prompt' => Yii::t('app', 'Anyone')]); ?>
<div class="row">
    <div class="col-xs-6">
        <?= $form->field($collection, 'filter_price_from')->textInput(); ?>
    </div>
    <div class="col-xs-6">
        <?= $form->field($collection, 'filter_price_to')->textInput(); ?>
    </div>
</div>
