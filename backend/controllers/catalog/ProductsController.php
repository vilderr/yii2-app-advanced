<?php

namespace backend\controllers\catalog;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\managers\catalog\ProductManager as Manager;
use common\repo\ProductRepository;
use common\repo\CategoryRepository;
use common\collects\catalog\ProductCollection as Collection;
use common\collects\catalog\ProductSearchCollection;

/**
 * Class ProductsController
 * @package backend\controllers\catalog
 */
class ProductsController extends Controller
{
    private $_manager;
    private $_products;
    private $_categories;

    public function __construct($id, $module, Manager $manager, ProductRepository $products, CategoryRepository $categories, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_manager = $manager;
        $this->_products = $products;
        $this->_categories = $categories;
    }

    public function actionIndex($category_id = null)
    {
        $category = null;
        if ($category_id) {
            $category = $this->_categories->get($category_id);
        }

        $searchCollection = new ProductSearchCollection(['category_id' => $category_id]);
        $dataProvider = $searchCollection->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchCollection' => $searchCollection,
            'dataProvider'     => $dataProvider,
            'category'         => $category,
        ]);
    }

    public function actionCreate($category_id = null)
    {
        $category = null;
        if ($category_id) {
            $category = $this->_categories->get($category_id);
        }

        $collection = new Collection(null, ['category_id' => $category_id]);
        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $product = $this->_manager->create($collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index', 'category_id' => $category_id]));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $product->id,
                            'returnUrl' => $returnUrl,
                        ]);
                }
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'collection' => $collection,
            'category'   => $category,
        ]);
    }

    public function actionUpdate($id)
    {
        $product = $this->_products->find($id);
        $collection = new Collection($product);

        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $this->_manager->edit($product, $collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index', 'category_id' => $product->category_id]));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $product->id,
                            'returnUrl' => $returnUrl,
                        ]);
                }
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'collection' => $collection,
        ]);
    }

    public function actionDelete($id, $category_id = null)
    {
        try {
            $product = $this->_products->get($id);
            $this->_manager->remove($product);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index', 'category_id' => $category_id]);
    }

    public function actionDeletePicture($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $result['success'] = 'success';
        try {
            $product = $this->_products->get($id);
            $product->picture->delete();
        } catch (\DomainException $e) {
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete'         => ['POST'],
                    'delete-picture' => ['POST'],
                ],
            ],
        ];
    }
}