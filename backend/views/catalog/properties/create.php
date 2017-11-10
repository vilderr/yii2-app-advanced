<?php
use common\collects\catalog\property\PropertyCollection as Collection;

/**
 * @var $this yii\web\View
 * @var $collection Collection
 */

$this->title = 'Свойства' . ': новое свойство';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => ['/catalog'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Свойства',
    'url'   => ['index'],
];
$this->params['breadcrumbs'][] = 'Создание';
?>
<div class="property-create">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]) ?>
</div>
