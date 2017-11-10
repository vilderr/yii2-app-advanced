<?php

use yii\helpers\Url;
use common\collects\catalog\distribution\DistributionProfileCollection as Collection;

/**
 * @var $this yii\web\View
 * @var $collection Collection
 */

$this->title = 'Профили распределения: новый профиль';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => Url::to(['/catalog']),
];
$this->params['breadcrumbs'][] = [
    'label' => 'Профили импорта',
    'url'   => Url::to(['index']),
];
$this->params['breadcrumbs'][] = 'Добавление';
?>
<?= $this->render('_form', ['collection' => $collection]); ?>
