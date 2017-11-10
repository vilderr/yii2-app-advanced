<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\data\ActiveDataProvider;
use frontend\widgets\LinkPager;
use common\models\catalog\category\Category;
use common\repo\CategoryRepository;
use frontend\widgets\SmartFilter;
use frontend\widgets\CategoryMenu;

/**
 * @var $this         View
 * @var $provider     ActiveDataProvider
 * @var $category     Category
 * @var $filterItems  array
 * @var $setFilter    bool
 * @var $categories   CategoryRepository
 */
?>
<div class="row">
    <div class="col-4">
        <?= CategoryMenu::widget([
            'category'        => $category,
            'options'         => [
                'id' => 'left-category-menu',
            ],
            'items'           => $categories->getChidrens($category, true)->all(),
            'route'           => \yii\helpers\Url::toRoute(['category', 'id' => $category->id]),
            'activateParents' => true,
        ]); ?>
        <?= SmartFilter::widget([
            'items'    => $filterItems,
            'category' => $category,
            'options'  => [
                'id' => 'product-filter',
            ],
            'isSet'    => $setFilter,
        ]); ?>
    </div>
    <div class="col-20">
        <div id="product-list">
            <div class="sort-box form-inline">
                <div class="form-group outer">
                    <label for="input-sort">Сортировать:</label>
                    <select id="input-sort" class="form-control" onchange="location = this.value;">
                        <?php
                        $values = [
                            '-updated_at' => 'По умолчанию',
                            'price'       => 'Сначала дешевые',
                            '-price'      => 'Сначала дорогие',
                        ];
                        $current = Yii::$app->request->get('sort');
                        ?>
                        <?php foreach ($values as $value => $label): ?>
                            <option value="<?= Html::encode(Url::current(['sort' => $value ?: null])) ?>"
                                    <?php if ($current == $value): ?>selected="selected"<?php endif; ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <?php foreach ($provider->getModels() as $product): ?>
                    <?= $this->render('_product', [
                        'product' => $product,
                    ]); ?>
                <? endforeach; ?>
            </div>
        </div>
        <div id="pagination">
            <?= LinkPager::widget([
                'pagination'               => $provider->pagination,
                'maxButtonCount'           => 7,
                'disableCurrentPageButton' => true,
                'prevPageLabel'            => '<i class="mdi mdi-chevron-left" aria-hidden="true"></i>',
                'nextPageLabel'            => '<i class="mdi mdi-chevron-right" aria-hidden="true"></i>',
                'options'                  => ['class' => 'pagination justify-content-center'],
            ]); ?>
        </div>
        <?php if ($category->description): ?>
            <?= Yii::$app->formatter->asHtml($category->description, [
                'Attr.AllowedRel'      => array('nofollow'),
                'HTML.SafeObject'      => true,
                'Output.FlashCompat'   => true,
                'HTML.SafeIframe'      => true,
                'URI.SafeIframeRegexp' => '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
            ]) ?>
        <? endif; ?>
    </div>
</div>
