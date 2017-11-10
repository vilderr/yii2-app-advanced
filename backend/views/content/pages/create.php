<?php
use yii\web\View;
use common\collects\content\PageCollection as Collection;

/**
 * @var $this       View
 * @var $collection Collection
 */
?>
<div class="backend-page-create">
    <?= $this->render('_form', [
        'collection' => $collection,
    ]) ?>
</div>
