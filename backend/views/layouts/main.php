<?php
/**
 * @var $this    \yii\web\View;
 * @var $content string
 */
use backend\assets\AppAsset;
AppAsset::register($this);
?>
<?php $this->beginContent('@backend/views/layouts/frame.php', ['bodyClass' => 'main flat sidebar-mini']); ?>
<div class="wrapper clearfix">
    <?= $this->render('inc/header.php'); ?>
    <?= $this->render('inc/aside.php'); ?>
    <?= $this->render('inc/content', ['content' => $content]); ?>
    <?= $this->render('inc/footer'); ?>
</div>
<?php $this->endContent(); ?>
