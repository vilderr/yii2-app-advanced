<?php

namespace backend\controllers\catalog;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\filters\VerbFilter;
use common\repo\CategoryRepository as Repository;
use common\managers\catalog\CategoryManager as Manager;
use common\collects\catalog\category\CategoryCollection as Collection;

/**
 * Class CategoryController
 * @package backend\controllers\catalog
 */
class SectionsController extends Controller
{
    private $_manager;
    private $_repository;

    public function __construct($id, $module, Repository $repository, Manager $manager, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_manager = $manager;
        $this->_repository = $repository;
    }

    public function actionIndex($id = 1)
    {
        $category = $this->_repository->find($id);

        $dataProvider = new ActiveDataProvider([
            'query' => $this->_repository->getDescendants($category),
        ]);

        return $this->render('index', [
            'category'     => $category,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($parent_id = 1)
    {
        $parent = $this->_repository->get($parent_id);

        $collection = new Collection($parent);
        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $item = $this->_manager->create($collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index', 'id' => $item->parent ? $item->parent->id : 0]));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $item->id,
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
            'parent'     => $parent,
        ]);
    }

    public function actionUpdate($id)
    {
        $category = $this->_repository->find($id);
        $collection = new Collection($category->parent, $category);

        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $this->_manager->edit($category, $collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index', 'id' => $category->parent ? $category->parent->id : 0]));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $category->id,
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
            'category'   => $category,
        ]);
    }

    public function actionDelete($id)
    {
        try {
            $category = $this->_repository->get($id);
            $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index', 'id' => $category->parent ? $category->parent->id : 0]));
            $this->_manager->remove($category);

            return $this->redirect($returnUrl);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionDeletePicture($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $result['success'] = 'success';
        try {
            $category = $this->_repository->get($id);
            $category->picture->delete();
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