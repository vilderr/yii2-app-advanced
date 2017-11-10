<?php
/**
 * @var $this    \yii\web\View
 * @var $content string
 */
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><?= Html::encode($this->title); ?></h1>
        <?= Breadcrumbs::widget([
            'links'    => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    </section>
    <section class="content">
        <?= \common\widgets\Alert::widget();?>
        <?= $content; ?>
    </section>
</div>
