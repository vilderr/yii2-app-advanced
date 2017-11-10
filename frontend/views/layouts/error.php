<?php
/**
 * @var $this    \yii\web\View;
 * @var $content string
 */

?>
<?php $this->beginContent('@frontend/views/layouts/main-frame.php'); ?>
<div class="container">
    <h1><?= $this->title; ?></h1>
    <?= $content; ?>
</div>
<?php $this->endContent(); ?>