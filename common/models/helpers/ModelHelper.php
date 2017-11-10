<?php

namespace common\models\helpers;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Class ModelHelper
 * @package common\models\helpers
 */
class ModelHelper
{
    const DEFAULT_SORT = 500;
    const STATUS_ACTIVE = 1;
    const STATUS_WAIT = 0;

    public static function statusList()
    {
        return [
            ModelHelper::STATUS_ACTIVE => 'Да',
            ModelHelper::STATUS_WAIT   => 'Нет',
        ];
    }

    public static function statusName($status)
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status)
    {
        switch ($status) {
            case ModelHelper::STATUS_WAIT:
                $class = 'label label-default';
                break;
            case ModelHelper::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }
}