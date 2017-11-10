<?php

namespace common\models\catalog\distribution;

use Yii;
use common\models\catalog\distribution\DistributionProfile as Profile;
use common\collects\catalog\distribution\DistributionProfilePartCollection as Collection;
use common\models\catalog\behaviors\DistributionProfilePartBehavior;

/**
 * This is the model class for table "{{%distribution_profile_parts}}".
 *
 * @property integer             $id
 * @property integer             $profile_id
 * @property string              $name
 * @property string              $filter_json
 * @property string              $action_json
 * @property integer             $sort
 * @property integer             $status
 *
 * @property DistributionProfile $profile
 */
class DistributionProfilePart extends \yii\db\ActiveRecord
{
    public $filter = [];
    public $action = [];

    public $filter_name;
    public $filter_category_id;
    public $filter_status;
    public $filter_price_from;
    public $filter_price_to;
    public $action_category_id;
    public $action_status;
    public $action_tags;

    /**
     * @param Collection $collection
     *
     * @return DistributionProfilePart
     */
    public static function create(Collection $collection)
    {
        return new static([
            'profile_id'         => $collection->profile_id,
            'name'               => $collection->name,
            'filter_name'        => $collection->filter_name,
            'filter_category_id' => $collection->filter_category_id,
            'filter_status'      => $collection->filter_status,
            'filter_price_from'  => $collection->filter_price_from,
            'filter_price_to'    => $collection->filter_price_to,
            'action_category_id' => $collection->action_category_id,
            'action_status'      => $collection->action_status,
            'action_tags'        => $collection->action_tags,
            'sort'               => $collection->sort,
            'status'             => $collection->status,
        ]);
    }

    /**
     * @param DistributionProfilePart $part
     * @param Collection              $collection
     */
    public function edit(DistributionProfilePart $part, Collection $collection)
    {
        $this->name = $collection->name;
        $this->filter_name = $collection->filter_name;
        $this->filter_category_id = $collection->filter_category_id;
        $this->filter_status = $collection->filter_status;
        $this->filter_price_from = $collection->filter_price_from;
        $this->filter_price_to = $collection->filter_price_to;
        $this->action_category_id = $collection->action_category_id;
        $this->action_status = $collection->action_status;
        $this->action_tags = $collection->action_tags;
        $this->sort = $collection->sort;
        $this->status = $collection->status;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%distribution_profile_parts}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'name'        => Yii::t('app', 'Name'),
            'profile_id'  => 'Distribution ID',
            'filter_json' => 'Filter Json',
            'action_json' => 'Action Json',
            'sort'        => Yii::t('app', 'Sort'),
            'status'      => Yii::t('app', 'Status'),
        ];
    }

    public function rules()
    {
        return [
            [['profile_id'], 'required'],
            [['profile_id', 'sort', 'status'], 'integer'],
            [['filter_json', 'action_json'], 'string'],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::className(), 'targetAttribute' => ['profile_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(DistributionProfile::className(), ['id' => 'profile_id']);
    }

    public function behaviors()
    {
        return [
            DistributionProfilePartBehavior::class,
        ];
    }
}
