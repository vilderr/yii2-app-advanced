<?php

namespace backend\controllers;

use Yii;
use common\managers\AuthManager as Manager;
use yii\web\Controller;
use common\collects\user\LoginCollection as Collection;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Class AuthController
 * @package backend\controllers
 */
class AuthController extends Controller
{
    public $layout = 'auth';

    private $_manager;

    public function __construct($id, $module, Manager $manager, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_manager = $manager;
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest && Yii::$app->user->can('admin')) {
            return $this->goHome();
        }

        $collection = new Collection();
        if ($collection->load(Yii::$app->request->post()) && $collection->validate()) {
            try {
                $user = $this->_manager->auth($collection);
                Yii::$app->user->login($user, $collection->rememberMe ? 3600 * 24 * 30 : 0);

                return $this->goBack();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('login', [
            'model' => $collection,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow'   => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
}