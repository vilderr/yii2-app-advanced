<?php
/**
 * @var $this    \yii\web\View;
 * @var $content string
 */

use frontend\widgets\Breadcrumbs;
?>
<?php $this->beginContent('@frontend/views/layouts/main-frame.php'); ?>
    <div class="container">
        <?= Breadcrumbs::widget([
            'tag'                => 'ol',
            'homeLink'           => [
                'label' => 'Главная',
                'url'   => '/',
            ],
            'links'              => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'itemTemplate'       => "<li class=\"breadcrumb-item\">{link}</li>\n",
            'activeItemTemplate' => "<li class=\"breadcrumb-item active\">{link}</li>\n",
        ]); ?>
        <h1><?= $this->title; ?></h1>
        <?= $content; ?>
    </div>
<?php $this->endContent(); ?>