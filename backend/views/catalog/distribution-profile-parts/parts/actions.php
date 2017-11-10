<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\collects\catalog\distribution\DistributionProfilePartCollection as Collection;
use common\repo\CategoryRepository;
use common\models\helpers\ModelHelper;
use kartik\widgets\Select2;

/**
 * @var $form       ActiveForm
 * @var $collection Collection
 */
?>
<?= $form->field($collection, 'action_category_id')->dropDownList(CategoryRepository::tree(), ['prompt' => Yii::t('app', 'Not choosed')]); ?>
<?= $form->field($collection, 'action_status')->dropDownList(ModelHelper::statusList(), ['prompt' => Yii::t('app', 'Anyone')]); ?>
<?= Html::hiddenInput($collection->formName() . '[action_tags]'); ?>
<label class="control-label"><?= $collection->getAttributeLabel('action_tags') ?></label>
<?= Select2::widget([
    'name'          => $collection->formName() . '[action_tags]',
    'value'         => $collection->action_tags,
    'data'          => \common\repo\TagRepository::getDropDownList(),
    'maintainOrder' => true,
    'options'       => [
        'multiple' => true,
    ],
    'pluginOptions' => [
        'tags'               => true,
        'maximumInputLength' => 10,
    ],
]);
?>
