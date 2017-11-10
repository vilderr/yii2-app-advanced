<?php

use common\models\catalog\product\Product;
use common\models\helpers\ProductHelper;

/**
 * @var $this    yii\web\View
 */

$this->title = 'My Yii Application';

$result = Product::find()
    ->alias('P')
    ->with('picture')
    ->joinWith('values V', true)
    ->limit(10)
    ->where(['P.status' => ProductHelper::STATUS_ACTIVE,'P.id' => [1], 'V.property_id' => 2])
    ->all();
//echo '<pre>'; print_r($result); echo '</pre>';
?>
