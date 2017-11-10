<?php

namespace common\collects\catalog\import;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\catalog\import\ImportProfile as Profile;

class ImportProfileSectionsCollection extends Model
{
    public $values = [];

    public function __construct(Profile $profile, array $config = [])
    {
        $this->values = ArrayHelper::getColumn($profile->sections, 'value');

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['values', 'each', 'rule' => ['safe']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'values' => 'Категории',
        ];
    }
}