<?php

use yii\helpers\Url;
use common\collects\catalog\import\ImportProfileCollection as Collection;

/**
 * @var $this       \yii\web\View
 * @var $collection Collection
 */

$this->title = 'Профили импорта: новый профиль';
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
<div class="catalog-import-profile-create">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]); ?>
</div>
