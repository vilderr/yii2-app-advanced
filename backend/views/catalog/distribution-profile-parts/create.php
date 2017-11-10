<?php

use common\collects\catalog\distribution\DistributionProfilePartCollection as Collection;

/**
 * @var $this       \yii\web\View
 * @var $collection Collection
 */
$this->title = '"' . $collection->profile->name . '": новая итерация';
$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url'   => ['/catalog'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Распределение товаров',
    'url'   => ['/catalog/distribution-profile'],
];
$this->params['breadcrumbs'][] = [
    'label' => $collection->profile->name,
    'url'   => ['index', 'profile_id' => $collection->profile->id],
];
$this->params['breadcrumbs'][] = 'Добавление';
?>
<div class="distribution-profile-part-create">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]) ?>
</div>

