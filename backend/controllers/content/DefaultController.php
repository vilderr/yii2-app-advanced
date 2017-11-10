<?php

namespace backend\controllers\content;

use yii\web\Controller;

/**
 * Class DefaultController
 * @package backend\controllers\content
 */
class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}