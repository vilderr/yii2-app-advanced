<?php

namespace common\models\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\base\Event;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\models\Meta;

/**
 * Class MetaBehavior
 * @package common\models\behaviors
 */
class MetaBehavior extends Behavior
{
    public $attribute = 'meta';
    public $jsonAttribute = 'meta_json';

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND    => 'onAfterFind',
            ActiveRecord::EVENT_BEFORE_INSERT => 'onBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'onBeforeSave',
        ];
    }

    /**
     * @param Event $event
     */
    public function onAfterFind(Event $event)
    {
        $model = $event->sender;
        $meta = Json::decode($model->getAttribute($this->jsonAttribute));
        $model->{$this->attribute} = new Meta(
            ArrayHelper::getValue($meta, 'title'),
            ArrayHelper::getValue($meta, 'description'),
            ArrayHelper::getValue($meta, 'keywords')
        );
    }

    /**
     * @param Event $event
     */
    public function onBeforeSave(Event $event)
    {
        $model = $event->sender;
        $model->setAttribute('meta_json', Json::encode([
            'title'       => $model->{$this->attribute}->title,
            'description' => $model->{$this->attribute}->description,
            'keywords'    => $model->{$this->attribute}->keywords,
        ]));
    }
}