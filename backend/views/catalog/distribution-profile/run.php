<?php
use yii\helpers\Url;
use yii\web\View;
use common\models\catalog\distribution\DistributionProfile as Profile;
use common\managers\catalog\ProductManager;

/**
 * @var $this           View
 * @var $profile        Profile
 * @var $productManager ProductManager
 */

$this->title = $profile->name;
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => Url::to(['/catalog']),
];
$this->params['breadcrumbs'][] = [
    'label' => 'Профили импорта',
    'url'   => Url::to(['index']),
];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= \backend\widgets\Distribution::widget([
    'model'          => $profile,
    'productManager' => $productManager,
]); ?>
