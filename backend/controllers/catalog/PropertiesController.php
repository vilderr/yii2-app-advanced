<?php

namespace backend\controllers\catalog;

use Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use common\managers\catalog\PropertyManager as Manager;
use common\repo\PropertyRepository as Repository;
use common\collects\catalog\property\PropertyCollection as Collection;
use common\collects\catalog\property\PropertySearchCollection;

/**
 * Class PropertiesController
 * @package backend\controllers\catalog
 */
class PropertiesController extends \yii\web\Controller
{
    private $_manager;
    private $_repository;

    public function __construct($id, $module, Manager $manager, Repository $repository, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_manager = $manager;
        $this->_repository = $repository;
    }

    public function actionIndex()
    {
        $collection = new PropertySearchCollection();
        $dataProvider = $collection->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'collection'   => $collection,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $collection = new Collection();
        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $property = $this->_manager->create($collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index']));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $property->id,
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
        ]);
    }

    public function actionUpdate($id)
    {
        $property = $this->_repository->find($id);
        $collection = new Collection($property);
        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $this->_manager->edit($property, $collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index']));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $property->id,
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
            'property'   => $property,
        ]);
    }

    public function actionDelete($id)
    {
        try {
            $property = $this->_repository->get($id);
            $this->_manager->remove($property);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
}
