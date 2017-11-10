<?php

namespace backend\controllers\catalog;

use Yii;
use yii\helpers\Url;
use common\repo\catalog\DistributionProfileRepository as Repository;
use common\managers\catalog\DistributionProfileManager as Manager;
use common\managers\catalog\ProductManager;
use common\collects\catalog\distribution\DistributionProfileCollection as Collection;
use common\collects\catalog\distribution\DistributionProfileSearchCollection as SearchCollection;

/**
 * Class DistributionProfileController
 * @package backend\controllers\catalog
 */
class DistributionProfileController extends \yii\web\Controller
{
    private $_repository;
    private $_manager;
    private $_productManager;

    public function __construct($id, $module, Repository $repository, Manager $manager, ProductManager $productManager, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_repository = $repository;
        $this->_manager = $manager;
        $this->_productManager = $productManager;
    }

    public function actionIndex()
    {
        $collection = new SearchCollection();
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
                $distribution = $this->_manager->create($collection);

                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index']));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $distribution->id,
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
        $profile = $this->_repository->find($id);
        $collection = new Collection($profile);
        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $this->_manager->edit($profile, $collection);

                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index']));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $profile->id,
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

    public function actionDelete($id)
    {
        try {
            $profile = $this->_repository->get($id);
            $this->_manager->remove($profile);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionRun($profile_id)
    {
        $profile = $this->_repository->find($profile_id);

        return $this->render('run', [
            'profile'        => $profile,
            'productManager' => $this->_productManager,
        ]);
    }
}
