<?php
/**
 * @var $this      \yii\web\View
 * @var $content   string
 * @var $bodyClass string
 */
use yii\helpers\Html;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body <?= isset($this->params['bodyClass']) ? 'class="'.implode(" ", $this->params['bodyClass']).'"': ''; ?>>
<?php $this->beginBody() ?>
<?= $content; ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
