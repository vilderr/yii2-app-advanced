<?php

use yii\helpers\Url;
use backend\models\BackendMenu;
use backend\collects\BackendMenuCollection as Collection;

/**
 * @var $this       yii\web\View
 * @var $item       BackendMenu
 * @var $collection Collection
 */
$this->title = $item->name . ': редактирование';
$this->params['breadcrumbs'][] = [
    'label' => 'Меню',
    'url'   => Url::toRoute(['index']),
];

foreach ($item->parents as $chain) {
    $this->params['breadcrumbs'][] = [
        'label' => $chain->name,
        'url'   => ['index', 'id' => $chain->id],
    ];
}

$this->params['breadcrumbs'][] = $item->name;
?>
<div class="menu-item-create">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]) ?>
</div>
