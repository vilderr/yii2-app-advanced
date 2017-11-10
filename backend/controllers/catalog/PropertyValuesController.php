<?php

namespace backend\controllers\catalog;

use common\collects\catalog\property\PropertyValueSearchCollection;
use Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use common\repo\PropertyValueRepository;
use common\repo\PropertyRepository;
use common\collects\catalog\property\PropertyValueCollection as Collection;
use common\managers\catalog\PropertyValueManager as Manager;

/**
 * Class PropertyValuesController
 * @package backend\controllers\catalog
 */
class PropertyValuesController extends \yii\web\Controller
{
    private $_manager;
    private $_values;
    private $_properties;

    public function __construct($id, $module, Manager $manager, PropertyValueRepository $values, PropertyRepository $properties, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_manager = $manager;
        $this->_values = $values;
        $this->_properties = $properties;
    }

    public function actionIndex($property_id)
    {
        $property = $this->_properties->get($property_id);
        $collection = new PropertyValueSearchCollection(['property' => $property]);
        $dataProvider = $collection->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'collection'   => $collection,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($property_id)
    {
        $property = $this->_properties->get($property_id);
        $collection = new Collection($property);

        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $value = $this->_manager->create($collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index', 'property_id' => $property->id]));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $value->id,
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
        $value = $this->_values->find($id);
        $collection = new Collection($value->property, $value);
        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $this->_manager->edit($value, $collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::toRoute(['index', 'property_id' => $value->property->id]));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $value->id,
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
        $value = $this->_values->get($id);

        try {
            $this->_manager->remove($value);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index', 'property_id' => $value->property->id]);
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
