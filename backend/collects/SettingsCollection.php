<?php

namespace backend\collects;

use yii\base\Model;

/**
 * Class SettingsCollection
 * @package backend\collects
 */
class SettingsCollection extends Model
{
    public $maintenance;

    public function __construct($options = null, array $config = [])
    {
        if ($options) {
            $this->load($options, 'app');
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['maintenance', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'maintenance' => 'Закрыть публичную часть для просмотра',
        ];
    }
}