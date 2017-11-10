<?php

use yii\helpers\Url;
use common\collects\catalog\distribution\DistributionProfileCollection as Collection;

/**
 * @var $this yii\web\View
 * @var $collection Collection
 */

$this->title = 'Профили распределения: редактирование';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => Url::to(['/catalog']),
];
$this->params['breadcrumbs'][] = [
    'label' => 'Профили распределения',
    'url'   => Url::to(['index']),
];
$this->params['breadcrumbs'][] = $collection->profile->name;
//echo '<pre>'; print_r($collection); echo '</pre>';
?>
<?= $this->render('_form', ['collection' => $collection]); ?>
