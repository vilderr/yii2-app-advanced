<?php

namespace backend\managers;

use Yii;
use yii\base\Model;
use common\components\Option;

/**
 * Class SettingsManager
 * @package backend\managers
 */
class SettingsManager
{
    private $_option;

    public function __construct(Option $option)
    {
        $this->_option = $option;
    }

    public function save(Model $collection)
    {
        foreach ($collection->attributes as $name => $value) {
            $this->_option->set('app', $name, $value);
        }
    }
}