<?php
use common\collects\TagCollection as Collection;

/**
 * @var $this yii\web\View
 * @var $collection Collection
 */

$this->title = 'Теги' . ': новый тег';
$this->params['breadcrumbs'][] = [
    'label' => 'Контент',
    'url'   => ['/content'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Теги',
    'url'   => ['index'],
];
$this->params['breadcrumbs'][] = 'Создание';
?>
<div class="tag-create">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]) ?>
</div>
