<?php
/**
 * @var $this \yii\web\View
 */
use yii\helpers\Html;
use yii\helpers\Url;

?>
<header class="main-header">
    <a href="<?= Url::to(['/']) ?>" class="logo" title="Панель администратора">
        <span class="logo-sm"><i class="mdi mdi-home"></i></span>
        <span class="logo-lg"><?= Yii::$app->name; ?></span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <button class="sidebar-toggle-button"><span></span></button>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="nav-item"><?= Html::a('Перейти на сайт', Url::to('/')) ?></li>
                <li class="nav-item"><?= Html::a('<i class="mdi mdi-power"></i>', ['/auth/logout'], ['data-method' => 'post'])?></li>
            </ul>
        </div>
    </nav>
</header>
