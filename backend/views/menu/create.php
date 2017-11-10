<?php

use yii\helpers\Url;
use backend\collects\BackendMenuCollection as Collection;

/**
 * @var $this       yii\web\View
 * @var $collection Collection
 * @var $parent     \backend\models\BackendMenu
 */

$this->title = ($parent ? $parent->name : 'Верхний уровень') . ': новый пункт меню';
if ($parent) {
    $this->params['breadcrumbs'][] = [
        'label' => 'Меню',
        'url'   => Url::toRoute(['index']),
    ];

    foreach ($parent->parents as $chain) {
        $this->params['breadcrumbs'][] = [
            'label' => $chain->name,
            'url'   => ['index', 'id' => $chain->id],
        ];
    }

    $this->params['breadcrumbs'][] = $parent->name;

} else {
    $this->params['breadcrumbs'][] = 'Меню';
}
?>
<div class="menu-item-create">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]) ?>
</div>
