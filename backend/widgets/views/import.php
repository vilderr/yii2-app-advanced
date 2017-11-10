<?php

use yii\helpers\Html;

/**
 * @var $this \yii\web\View
 */
?>
<div class="panel panel-default flat">
    <div class="panel-body" id="import-result-container">
        <p class="lead">Для начала импорта нажмите кнопку "Запустить".</p>
        <p>Не запускайте один профиль импорта в нескольких вкладках браузера!<br>Вы можете остановить операцию во время
            импорта, нажав кнопку "Остановить".</p>
    </div>
    <div class="panel-footer">
        <?= Html::submitButton('Запустить', [
            'id'      => 'start-button',
            'class'   => 'btn btn-primary btn-flat',
            'onclick' => 'StartImport();',
        ]); ?>
        <?= Html::submitButton('Остановить', [
            'class'   => 'btn btn-default btn-flat',
            'onclick' => 'EndImport();',
        ]); ?>
        <?= Html::a('Профили импорта', ['index'], ['class' => 'btn btn-default btn-flat pull-right']); ?>
    </div>
</div>
