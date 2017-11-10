<?php
use common\collects\catalog\property\PropertyCollection as Collection;
use common\models\catalog\property\Property;

/**
 * @var $this       yii\web\View
 * @var $collection Collection
 * @var $property   Property
 */

$this->title = 'Свойства' . ': редактирование';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => ['/catalog'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Свойства',
    'url'   => ['index'],
];
$this->params['breadcrumbs'][] = $property->name;
?>
<div class="property-create">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]) ?>
</div>
