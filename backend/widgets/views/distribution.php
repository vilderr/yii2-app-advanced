<?php

use yii\helpers\Html;
use common\models\catalog\distribution\DistributionProfile as Profile;

/**
 * @var $this  \yii\web\View
 * @var $profile Profile
 */
?>
<div class="panel panel-default flat">
    <div class="panel-body" id="distribution-result-container">
        <p class="lead">Для начала распределения нажмите кнопку "Запустить".</p>
        <p>Не запускайте скрипт в нескольких вкладках браузера!<br>Вы можете остановить операцию во время распределения,
            нажав кнопку "Остановить".</p>
    </div>
    <div class="panel-footer">
        <?= Html::submitButton(Yii::t('app', 'Start'), [
            'id'      => 'start-button',
            'class'   => 'btn btn-primary btn-flat',
            'onclick' => 'StartDistribution();',
        ]); ?>
        <?= Html::submitButton(Yii::t('app', 'Stop'), [
            'class'   => 'btn btn-default btn-flat',
            'onclick' => 'EndDistribution();',
        ]); ?>
        <div class="pull-right">
            <?= Html::a(Yii::t('app','Distribution Profile Parts'), ['/catalog/distribution-profile-parts/', 'profile_id' => $profile->id], ['class' => 'btn btn-default btn-flat']); ?>
        </div>
    </div>
</div>
