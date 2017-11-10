<?php
use yii\helpers\Html;

$this->title = 'Закрыто на обслуживание';
?>
<div class="site-offline">
    <div class="status"><span class="mdi mdi-lock" aria-hidden="true"></span></div>
    <h1>Закрыто на обслуживание</h1>
    <p class="small"><?= Html::a('Я администратор', '/admin/'); ?></p>
</div>
