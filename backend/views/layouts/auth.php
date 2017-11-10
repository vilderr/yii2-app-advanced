<?php
/**
 * @var $this    \yii\web\View;
 * @var $content string
 */
use backend\assets\AuthAsset;

AuthAsset::register($this);
?>
<?php $this->beginContent('@backend/views/layouts/frame.php', ['bodyClass' => 'auth flat']); ?>
    <div class="container wrapper">
        <?= $content; ?>
    </div>
<?php $this->endContent(); ?>