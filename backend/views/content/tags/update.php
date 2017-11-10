<?php
use common\collects\TagCollection as Collection;
use common\models\Tag;

/**
 * @var $this       yii\web\View
 * @var $collection Collection
 * @var $tag        Tag
 */

$this->title = 'Теги' . ': редактирование';
$this->params['breadcrumbs'][] = [
    'label' => 'Контент',
    'url'   => ['/content'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Теги',
    'url'   => ['index'],
];
$this->params['breadcrumbs'][] = $tag->name;
?>
<div class="tag-create">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]) ?>
</div>
