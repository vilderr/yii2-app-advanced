<?php

namespace backend\controllers\content;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\models\content\MainSlider as Slide;
use common\repo\content\MainSliderRepository as Repository;
use common\managers\content\MainSliderManager as Manager;
use common\collects\content\MainSliderCollection as Collection;

/**
 * Class MainSliderController
 * @package backend\controllers\content
 */
class MainSliderController extends Controller
{
    private $_repository;
    private $_manager;

    public function __construct($id, $module, Repository $repository, Manager $manager, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->_repository = $repository;
        $this->_manager = $manager;
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Slide::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $collection = new Collection();
        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $slide = $this->_manager->create($collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index']));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $slide->id,
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
        $slide = $this->_repository->find($id);
        $collection = new Collection($slide);

        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $this->_manager->edit($slide, $collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index']));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $slide->id,
                            'returnUrl' => $returnUrl,
                        ]);
                }
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'collection' => $collection
        ]);
    }

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

    public function actionDeletePicture($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $result['success'] = 'success';
        try {
            $slide = $this->_repository->get($id);
            $slide->picture->delete();
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
                    'delete' => ['POST'],
                    'delete-picture' => ['POST'],
                ],
            ],
        ];
    }
}