<?php
use yii\web\View;
use yii\helpers\Url;
use common\models\catalog\category\Category;
use common\repo\CategoryRepository;
use yii\data\ActiveDataProvider;

/**
 * @var $this         View
 * @var $category     Category
 * @var $dataProvider ActiveDataProvider
 * @var $filterItems  array
 * @var $setFilter    bool
 * @var $categories   CategoryRepository
 */

$this->title = $category->name;
if ($category->id > 1) {
    $this->params['breadcrumbs'][] = [
        'label' => 'Каталог',
        'url'   => Url::toRoute(['index']),
    ];
    foreach ($category->chainTree as $chunk) {
        $this->params['breadcrumbs'][] = [
            'label' => $chunk->name,
            'url'   => Url::toRoute(['category', 'id' => $chunk->id]),
        ];
    }
    $this->params['breadcrumbs'][] = $category->name;
} else {
    $this->params['breadcrumbs'][] = 'Каталог';
}
?>
<?= $this->render('_list', [
    'provider'    => $dataProvider,
    'category'    => $category,
    'filterItems' => $filterItems,
    'setFilter'   => $setFilter,
    'categories'  => $categories,
]); ?>
