<?php

namespace frontend;

use Yii;
use yii\base\BootstrapInterface;

/**
 * Class SetUp
 * @package frontend
 */
class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = Yii::$container;

        if (!Yii::$app->user->can('admin')) {
            //$app->catchAll = ['app/offline'];
        }
    }
}