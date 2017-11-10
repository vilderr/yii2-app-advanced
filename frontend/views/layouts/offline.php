<?php
/**
 * @var $this    \yii\web\View;
 * @var $content string
 */
use frontend\assets\LockAsset;

LockAsset::register($this);
?>
<?php $this->beginContent('@frontend/views/layouts/frame.php', ['bodyClass' => 'lock flat']); ?>
    <div class="container wrapper">
        <?= $content; ?>
    </div>
<?php $this->endContent(); ?>