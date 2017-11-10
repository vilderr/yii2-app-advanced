<?php

use yii\helpers\Url;
use common\models\catalog\import\ImportProfile as Profile;
use common\managers\catalog\ProductManager;
use common\managers\catalog\PropertyManager;
use common\managers\catalog\PropertyValueManager;
use backend\widgets\Import;

/**
 * @var $this                 \yii\web\View
 * @var $profile              Profile
 * @var $productManager       ProductManager
 * @var $propertyManager      PropertyManager
 * @var $propertyValueManager PropertyValueManager
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
$this->params['breadcrumbs'][] = $profile->name;
?>
<?= Import::widget([
    'model'                => $profile,
    'productManager'       => $productManager,
    'propertyManager'      => $propertyManager,
    'propertyValueManager' => $propertyValueManager,
]); ?>
