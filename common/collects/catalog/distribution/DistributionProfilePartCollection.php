<?php

namespace common\collects\catalog\distribution;

use Yii;
use yii\base\Model;
use common\models\catalog\distribution\DistributionProfile as Profile;
use common\models\catalog\distribution\DistributionProfilePart as Part;
use common\models\helpers\ModelHelper;

/**
 * Class DistributionProfilePartCollection
 * @package common\collects\catalog\distribution
 */
class DistributionProfilePartCollection extends Model
{
    public $profile_id;
    public $name;

    public $filter_name;
    public $filter_category_id;
    public $filter_status;
    public $filter_price_from;
    public $filter_price_to;
    public $action_category_id;
    public $action_status;
    public $action_tags;

    public $sort;
    public $status;

    private $_profile;
    private $_part;

    public function __construct(Profile $profile, Part $part = null, array $config = [])
    {
        if ($part) {
            $this->name = $part->name;
            $this->filter_name = $part->filter_name;
            $this->filter_category_id = $part->filter_category_id;
            $this->filter_status = $part->filter_status;
            $this->filter_price_from = $part->filter_price_from;
            $this->filter_price_to = $part->filter_price_to;
            $this->action_category_id = $part->action_category_id;
            $this->action_status = $part->action_status;
            $this->action_tags = $part->action_tags;
            $this->sort = $part->sort;
            $this->status = $part->status;
        } else {
            $this->sort = ModelHelper::DEFAULT_SORT;
            $this->status = ModelHelper::STATUS_ACTIVE;
        }

        $this->profile_id = $profile->id;
        $this->_profile = $profile;
        $this->_part = $part;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'filter_name'], 'string'],
            [['sort', 'filter_category_id', 'filter_status', 'filter_price_from', 'filter_price_to', 'action_category_id', 'action_status', 'sort'], 'integer'],
            [['status'], 'boolean'],
            [['action_tags'], 'each', 'rule' => ['integer']],
        ];
    }

    public function getProfile()
    {
        return $this->_profile;
    }

    public function getPart()
    {
        return $this->_part;
    }

    public function attributeLabels()
    {
        return [
            'name'               => Yii::t('app', 'Name'),
            'sort'               => Yii::t('app', 'Sort'),
            'status'             => Yii::t('app', 'Status'),
            'filter_name'        => Yii::t('app', 'Name'),
            'filter_category_id' => 'Категория',
            'filter_status'      => Yii::t('app', 'Status'),
            'filter_price_from'  => Yii::t('app', 'Price From'),
            'filter_price_to'    => Yii::t('app', 'Price To'),
            'action_category_id' => 'Перенести в категорию',
            'action_status'      => Yii::t('app', 'Status'),
            'action_tags'        => Yii::t('app', 'Tags'),
        ];
    }
}