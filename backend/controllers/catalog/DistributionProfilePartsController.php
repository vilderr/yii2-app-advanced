<?php

namespace backend\controllers\catalog;

use Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use common\managers\catalog\DistributionProfilePartManager as Manager;
use common\repo\catalog\DistributionProfileRepository as Profiles;
use common\repo\catalog\DistributionProfilePartRepository as Parts;
use common\collects\catalog\distribution\DistributionProfilePartCollection as Collection;
use common\collects\catalog\distribution\DistributionProfilePartSearchCollection as SearchCollection;

/**
 * Class DistributionProfilePartsController
 * @package backend\controllers\catalog
 */
class DistributionProfilePartsController extends \yii\web\Controller
{
    private $_manager;
    private $_profiles;
    private $_parts;

    public function __construct($id, $module, Manager $manager, Profiles $profiles, Parts $parts, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_manager = $manager;
        $this->_profiles = $profiles;
        $this->_parts = $parts;
    }

    public function actionIndex($profile_id)
    {
        $profile = $this->_profiles->get($profile_id);
        $collection = new SearchCollection(['profile_id' => $profile_id]);
        $dataProvider = $collection->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'collection'   => $collection,
            'dataProvider' => $dataProvider,
            'profile'      => $profile,
        ]);
    }

    public function actionCreate($profile_id)
    {
        $profile = $this->_profiles->get($profile_id);
        $collection = new Collection($profile);
        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $part = $this->_manager->create($collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index', 'profile_id' => $profile->id]));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $part->id,
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
        $part = $this->_parts->find($id);
        $collection = new Collection($part->profile, $part);
        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $this->_manager->edit($part, $collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index', 'profile_id' => $part->profile->id]));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $part->id,
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
        $part = $this->_parts->get($id);
        try {
            $this->_manager->remove($part);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index', 'profile_id' => $part->profile->id]);
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
