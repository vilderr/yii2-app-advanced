<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use backend\managers\MenuManager as Manager;
use backend\collects\BackendMenuCollection as Collection;
use backend\repo\BackendMenuRepository as Repository;

/**
 * Class MenuController
 * @package app\controllers
 */
class MenuController extends Controller
{
    private $_manager;
    private $_repository;

    public function __construct($id, $module, Repository $repository, Manager $manager, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_manager = $manager;
        $this->_repository = $repository;
    }

    /**
     * @param int $id
     *
     * @return string
     */
    public function actionIndex($id = 0)
    {
        $item = null;

        if (intval($id) > 0) {
            $item = $this->_repository->find($id);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $this->_repository->getDescendants($item),
        ]);

        return $this->render('index', [
            'item'         => $item,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $parent_id
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate($parent_id = 0)
    {
        $parent = null;
        if (intval($parent_id) > 0) {
            $parent = $this->_repository->get($parent_id);
        }

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

    /**
     * @param $id
     *
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $item = $this->_repository->find($id);
        $collection = new Collection($item->parent, $item);

        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $this->_manager->edit($item, $collection);
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

        return $this->render('update', [
            'collection' => $collection,
            'item'       => $item,
        ]);
    }

    /**
     * @param $id
     *
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        try {
            $item = $this->_repository->get($id);
            $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index', 'id' => $item->parent ? $item->parent->id : 0]));
            $this->_manager->remove($item);

            return $this->redirect($returnUrl);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * @inheritdoc
     */
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
