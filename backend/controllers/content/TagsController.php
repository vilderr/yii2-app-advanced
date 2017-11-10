<?php

namespace backend\controllers\content;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\collects\TagCollection as Collection;
use common\repo\TagRepository as Repository;
use common\managers\TagManager as Manager;
use common\collects\TagSearchCollection;

/**
 * Class TagsController
 * @package backend\controllers
 */
class TagsController extends \yii\web\Controller
{
    private $_manager;
    private $_repository;

    /**
     * TagsController constructor.
     *
     * @param string           $id
     * @param \yii\base\Module $module
     * @param Manager          $manager
     * @param Repository       $repository
     * @param array            $config
     */
    public function __construct($id, $module, Manager $manager, Repository $repository, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_manager = $manager;
        $this->_repository = $repository;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $collection = new TagSearchCollection();
        $dataProvider = $collection->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'collection'   => $collection,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $collection = new Collection();
        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $item = $this->_manager->create($collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index']));
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
        ]);
    }

    /**
     * @param $id
     *
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $tag = $this->_repository->find($id);
        $collection = new Collection($tag);
        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $this->_manager->edit($tag, $collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index']));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $tag->id,
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
            'tag'        => $tag,
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
            $tag = $this->_repository->find($id);
            $this->_manager->remove($tag);
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
