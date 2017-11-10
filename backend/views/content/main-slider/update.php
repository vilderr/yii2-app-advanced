<?php
use yii\web\View;
use common\collects\content\MainSliderCollection as Collection;

/**
 * @var View
 * @var $collection Collection
 */

$this->title = 'Слайдер' . ': редактирование';
$this->params['breadcrumbs'][] = [
    'label' => 'Контент',
    'url'   => ['/content'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Cлайдер',
    'url'   => ['index'],
];
$this->params['breadcrumbs'][] = $collection->text;
?>
<div class="main-slider-create">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]) ?>
</div>
