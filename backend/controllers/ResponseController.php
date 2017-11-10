<?php

namespace backend\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use common\helpers\StringHelper;

/**
 * Class ResponseController
 * @package backend\controllers
 */
class ResponseController extends Controller
{
    public function actionMakeSlug($str, $replace_space, $replace_other)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return StringHelper::translit($str, 'ru', ['replace_space' => $replace_space, 'replace_other' => $replace_other]);
    }
}