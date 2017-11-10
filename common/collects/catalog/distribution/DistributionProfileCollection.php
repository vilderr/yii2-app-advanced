<?php

namespace common\collects\catalog\distribution;

use Yii;
use yii\base\Model;
use common\models\helpers\ModelHelper;
use common\models\catalog\distribution\DistributionProfile as Profile;

/**
 * Class DistributionProfileCollection
 * @package common\collects\catalog\distribution
 */
class DistributionProfileCollection extends Model
{
    public $name;
    public $description;
    public $sort;
    public $status;

    public $_profile;

    public function __construct(Profile $profile = null, array $config = [])
    {
        if ($profile) {
            $this->name = $profile->name;
            $this->description = $profile->description;
            $this->sort = $profile->sort;
            $this->status = $profile->status;
        } else {
            $this->sort = ModelHelper::DEFAULT_SORT;
            $this->status = ModelHelper::STATUS_ACTIVE;
        }

        $this->_profile = $profile;
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['sort'], 'integer'],
            ['sort', 'default', 'value' => ModelHelper::DEFAULT_SORT],
            [['name', 'description'], 'string', 'max' => 255],
            ['status', 'boolean'],
        ];
    }

    public function getProfile()
    {
        return $this->_profile;
    }

    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'name'        => 'Название',
            'description' => 'Описание',
            'sort'        => Yii::t('app', 'Sort'),
            'status'      => Yii::t('app', 'Status'),
        ];
    }
}