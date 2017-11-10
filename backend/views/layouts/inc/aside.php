<?php
/**
 * @var $this \yii\web\View
 */
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <?= \backend\widgets\BackendMenu::widget([
            'options'         => [
                'class' => 'sidebar-menu',
            ],
            'items'           => \backend\repo\BackendMenuRepository::getTree(),
            'submenuTemplate' => '<ul class="treeview-menu">{items}</ul>',
            'activateParents' => true
        ]); ?>
    </section>
</aside>
