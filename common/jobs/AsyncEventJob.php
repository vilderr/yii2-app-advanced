<?php

namespace common\jobs;

use yii\base\BaseObject;

class AsyncEventJob extends BaseObject implements \yii\queue\Job
{
    public $event;

    public function __construct($event, array $config = [])
    {
        parent::__construct($config);
        $this->event = $event;
    }

    public function execute($queue)
    {
        $listener = $this->resolveHandler();
        $listener($this, $queue);
    }

    /**
     * @return callable
     */
    private function resolveHandler()
    {
        return [\Yii::createObject(static::class . 'Handler'), 'handle'];
    }
}