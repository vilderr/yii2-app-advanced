<?php

namespace frontend\controllers;

use common\models\events\EntityViewed;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\repo\ProductRepository as Products;
use common\repo\CategoryRepository as Categories;
use common\repo\PropertyRepository as Properties;
use common\dispatchers\EventDispatcher;
use frontend\models\catalog\SmartFilter;

/**
 * Class CatalogController
 * @package frontend\controllers
 */
class CatalogController extends Controller
{
    private $_products;
    private $_categories;
    private $_properties;
    private $_dispatcher;

    public function __construct($id, $module, Products $products, Categories $categories, Properties $properties, EventDispatcher $dispatcher, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_products = $products;
        $this->_categories = $categories;
        $this->_properties = $properties;
        $this->_dispatcher = $dispatcher;
    }

    public function actionCategory($id, array $sef)
    {
        $category = $this->_categories->find($id);
        $properties = $this->_properties->getCategoryProperties($category);
        if (!$this->checkSef($sef, $properties)) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $filter = new SmartFilter($category, $properties, $sef);
        $dataProvider = $this->_products->getByFilter($filter->pagination, $filter->ids, $filter->totalCount);

        return $this->render('category', [
            'category'     => $category,
            'dataProvider' => $dataProvider,
            'filterItems'  => $filter->items,
            'setFilter'    => $filter->isSet(),
            'categories' => $this->_categories
        ]);
    }

    public function actionProduct($id)
    {
        $this->layout = 'product';
        $product = $this->_products->findForDetail($id);
        $properties = $this->_products->getValuesArray($product);
        $this->_dispatcher->dispatch(new EntityViewed($product));

        return $this->render('product', [
            'product'    => $product,
            'properties' => $properties,
        ]);
    }

    public function actionSearch()
    {

    }

    public function actionGoto($id)
    {
        $product = $this->_products->get($id);

        $this->redirect($product->url);
    }

    private function checkSef(array $sef, array $properties)
    {
        $t = [];
        $p = [];
        foreach ($sef as $k => $v) {
            $t[] = $k;
        }

        foreach ($properties as $property) {
            if (in_array($property['slug'], $t) && $property['sef'])
                $p[] = $property['slug'];
        }

        return $t === $p;
    }
}