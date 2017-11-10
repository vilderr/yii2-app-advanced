<?php
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use common\collects\content\PageCollection as Collection;

/**
 * @var $collection Collection
 */
?>
<div class="backend-page-form flat">
    <?php $form = ActiveForm::begin(); ?>

    <?= \yii\bootstrap\Tabs::widget([
        'id'          => 'page-form-tabs',
        'linkOptions' => [
            'class' => 'flat',
        ],
        'items'       => [
            [
                'label'   => 'Основные настройки',
                'content' => $this->render('parts/common', ['form' => $form, 'collection' => $collection]),
            ],
            [
                'label'   => 'Настройки SEO',
                'content' => $this->render('parts/seo', ['form' => $form, 'collection' => $collection]),
            ],
        ],
    ]); ?>

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
    </div>

    <?php ActiveForm::end(); ?>
</div>
