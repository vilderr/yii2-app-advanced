<?php

use yii\helpers\Url;
use common\collects\catalog\import\ImportProfileCollection as Collection;
use common\models\catalog\import\ImportProfile as Profile;

/**
 * @var $this           \yii\web\View
 * @var $profile Profile
 * @var $collection     Collection
 * @var $remoteSections array
 * @var $tab            string
 */

$this->title = 'Профили импорта: редактирование';
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
<div class="catalog-import-profile-update">
    <?= $this->render('_form', [
        'collection'     => $collection,
        'remoteSections' => $remoteSections,
        'tab'            => $tab,
    ]); ?>
</div>
