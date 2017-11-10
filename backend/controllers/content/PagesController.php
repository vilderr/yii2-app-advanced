<?php

namespace backend\controllers\content;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\managers\content\PageManager as Manager;
use common\repo\content\PageRepository as Repository;
use common\collects\content\PageCollection as Collection;

/**
 * Class PagesController
 * @package backend\controllers\content
 */
class PagesController extends Controller
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
        return $this->render('index');
    }

    public function actionCreate($parent_id = null)
    {
        $parent = null;
        if ($parent_id) {
            $parent = $this->_repository->get($parent_id);
        }

        $collection = new Collection($parent);
        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $page = $this->_manager->create($collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index', 'id' => $page->parent ? $page->parent->id : 0]));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $page->id,
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