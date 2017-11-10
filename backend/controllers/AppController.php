<?php

namespace backend\controllers;

use common\managers\catalog\CategoryManager;
use common\repo\PropertyRepository;
use Yii;
use yii\web\Controller;

/**
 * App controller
 */
class AppController extends Controller
{
    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
