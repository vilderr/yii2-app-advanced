<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\collects\user\LoginCollection as Collection;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model Collection */

$this->title = 'Авторизация';
$fieldOptions1 = [
    'options'       => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='mdi mdi-email-outline form-control-feedback'></span>",
];

$fieldOptions2 = [
    'options'       => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='mdi mdi-lock-open-outline form-control-feedback'></span>",
];
?>
<div id="login-wrapper" class="row">
    <div class="col-lg-4 col-md-5 col-sm-6 col-xs-10">
        <div class="login-box">
            <div class="text-center">
                <div class="h2">
                    <span><b class="text-uppercase"><?= Yii::$app->name; ?></b> admin</span>
                </div>
            </div>
            <div class="login-box-body">
                <p class="text-center">Пожалуйста авторизуйтесь</p>
                <?= \common\widgets\Alert::widget(); ?>
                <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

                <?= $form
                    ->field($model, 'username', $fieldOptions1)
                    ->label(false)
                    ->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('username')]) ?>

                <?= $form
                    ->field($model, 'password', $fieldOptions2)
                    ->label(false)
                    ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
