<?php

namespace common\models\catalog\behaviors;

use yii\base\Event;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\models\catalog\Meta;

class MetaBehavior extends \common\models\behaviors\MetaBehavior
{
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
            ArrayHelper::getValue($meta, 'keywords'),
            ArrayHelper::getValue($meta, 'product_title'),
            ArrayHelper::getValue($meta, 'product_description'),
            ArrayHelper::getValue($meta, 'product_keywords')
        );
    }

    /**
     * @param Event $event
     */
    public function onBeforeSave(Event $event)
    {
        $model = $event->sender;
        $model->setAttribute('meta_json', Json::encode([
            'title'               => $model->{$this->attribute}->title,
            'description'         => $model->{$this->attribute}->description,
            'keywords'            => $model->{$this->attribute}->keywords,
            'product_title'       => $model->{$this->attribute}->product_title,
            'product_description' => $model->{$this->attribute}->product_description,
            'product_keywords'    => $model->{$this->attribute}->product_keywords,
        ]));
    }
}