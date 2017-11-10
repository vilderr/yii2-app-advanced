<?php
use yii\helpers\Html;
use common\collects\catalog\property\PropertyValueCollection as Collection;

/**
 * @var $this       yii\web\View
 * @var $collection Collection
 */

$this->title = 'Значения свойства "'.$collection->property->name.'": редактирование';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => ['/catalog'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Свойства',
    'url'   => ['index'],
];
$this->params['breadcrumbs'][] = [
    'label' => $collection->property->name,
    'url' => ['index', 'property_id' => $collection->property->id]
];
$this->params['breadcrumbs'][] = $collection->value->name;
?>
<div class="property-value-edit">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]) ?>
</div>
