<?php

use common\collects\catalog\distribution\DistributionProfilePartCollection as Collection;

/**
 * @var $this       \yii\web\View
 * @var $collection Collection
 */
$this->title = '"' . $collection->profile->name . '": редактирование';
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
$this->params['breadcrumbs'][] = $collection->part->name;
?>
<div class="distribution-profile-part-create">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]) ?>
</div>

