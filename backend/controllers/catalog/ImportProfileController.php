<?php

namespace backend\controllers\catalog;

use Yii;
use yii\helpers\Url;
use common\managers\catalog\ImportProfileManager as Manager;
use common\repo\catalog\ImportProfileRepository as Repository;
use common\collects\catalog\import\ImportProfileCollection as Collection;
use common\collects\catalog\import\ImportProfileSearchCollection as SearchCollection;
use common\models\helpers\ImportProfileHelper;
use common\managers\catalog\ProductManager;
use common\managers\catalog\PropertyManager;
use common\managers\catalog\PropertyValueManager;

/**
 * Class ImportProfileController
 * @package backend\controllers\catalog
 */
class ImportProfileController extends \yii\web\Controller
{
    private $_manager;
    private $_productManager;
    private $_propertyManager;
    private $_propertyValueManager;
    private $_repository;

    public function __construct
    (
        $id,
        $module,
        Manager $manager,
        ProductManager $productManager,
        PropertyManager $propertyManager,
        PropertyValueManager $propertyValueManager,
        Repository $repository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->_manager = $manager;
        $this->_repository = $repository;
        $this->_productManager = $productManager;
        $this->_propertyManager = $propertyManager;
        $this->_propertyValueManager = $propertyValueManager;
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
                $profile = $this->_manager->create($collection);

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

        return $this->render('create', [
            'collection' => $collection,
        ]);
    }

    public function actionUpdate($id, $tab = null)
    {
        $tab = $tab ? $tab : 'common';
        $profile = $this->_repository->find($id);
        $collection = new Collection($profile);
        $remoteSections = [];

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

        try {
            $remoteSections = ImportProfileHelper::getRemoteSections($profile->sections_url, $profile->api_key);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->render('update', [
            'profile'        => $profile,
            'collection'     => $collection,
            'remoteSections' => $remoteSections,
            'tab'            => $tab,
        ]);
    }

    public function actionRun($id)
    {
        $profile = $this->_repository->get($id);

        return $this->render('run', [
            'profile'              => $profile,
            'productManager'       => $this->_productManager,
            'propertyManager'      => $this->_propertyManager,
            'propertyValueManager' => $this->_propertyValueManager,
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
}
