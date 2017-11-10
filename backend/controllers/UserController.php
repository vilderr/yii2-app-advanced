<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\models\user\User;
use common\managers\UserManager as Manager;
use common\collects\user\UserCollection as Collection;
use common\collects\user\UserSearchCollection;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    private $_manager;
    private $_repository;

    public function __construct($id, $module, Manager $manager, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_manager = $manager;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $collection = new UserSearchCollection();
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
                $user = $this->_manager->create($collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::to(['index']));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $user->id,
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
        $user = $this->findModel($id);
        $collection = new Collection($user);

        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $this->_manager->edit($user, $collection);
                $returnUrl = Yii::$app->request->get('returnUrl', Url::to(['index']));
                switch (Yii::$app->request->post('action', 'save')) {
                    case 'save':
                        return $this->redirect($returnUrl);
                    default:
                        return $this->redirect([
                            'update',
                            'id'        => $user->id,
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
            'user'       => $user,
        ]);
    }

    /**
     * @param $id
     *
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $this->_manager->remove($this->findModel($id));

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     *
     * @return User
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($user = User::findOne($id)) !== null) {
            return $user;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
