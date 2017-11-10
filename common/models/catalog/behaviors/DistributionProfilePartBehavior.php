<?php

namespace common\models\catalog\behaviors;

use yii\base\Behavior;
use yii\helpers\Json;
use yii\base\Event;
use yii\db\ActiveRecord;
use common\models\catalog\distribution\DistributionProfilePart as Part;

/**
 * Class DistributionProfilePartBehavior
 * @package common\models\catalog\behaviors
 */
class DistributionProfilePartBehavior extends Behavior
{
    public $filterAttribute = 'filter';
    public $jsonFilterAttribute = 'filter_json';
    public $actionAttribute = 'action';
    public $jsonOperationAttribute = 'action_json';

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND    => 'onInit',
            ActiveRecord::EVENT_BEFORE_INSERT => 'onBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'onBeforeSave',
        ];
    }

    /**
     * @param Event $event
     *
     * @return void
     */
    public function onInit(Event $event)
    {
        /**
         * @var $model Part
         */
        $model = $event->sender;
        $dataFilter = Json::decode($model->getAttribute($this->jsonFilterAttribute));
        $dataOperation = Json::decode($model->getAttribute($this->jsonOperationAttribute));

        $model->filter_name = $dataFilter['filter_name'];
        $model->filter_category_id = $dataFilter['filter_category_id'];
        $model->filter_status = $dataFilter['filter_status'];
        $model->filter_price_from = $dataFilter['filter_price_from'];
        $model->filter_price_to = $dataFilter['filter_price_to'];
        $model->action_category_id = $dataOperation['action_category_id'];
        $model->action_status = $dataOperation['action_status'];
        $model->action_tags = $dataOperation['action_tags'];
    }

    /**
     * @param Event $event
     *
     * @return void
     */
    public function onBeforeSave(Event $event)
    {
        /**
         * @var $model Part
         */
        $model = $event->sender;

        $model->setAttributes([
            $this->jsonFilterAttribute    => Json::encode([
                'filter_name'        => $model->filter_name,
                'filter_category_id' => $model->filter_category_id,
                'filter_status'      => $model->filter_status,
                'filter_price_from'  => $model->filter_price_from,
                'filter_price_to'    => $model->filter_price_to,
            ]),
            $this->jsonOperationAttribute => Json::encode([
                'action_category_id' => $model->action_category_id,
                'action_status'      => $model->action_status,
                'action_tags'        => $model->action_tags,
            ]),
        ]);
    }
}