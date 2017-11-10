<?php


namespace common\jobs;


use yii\base\BaseObject;

class Job extends BaseObject implements \yii\queue\Job
{
    public function execute($queue)
    {
        $listener = $this->resolveHandler();
        $listener($this, $queue);
    }

    private function resolveHandler(): callable
    {
        return [\Yii::createObject(static::class . 'Handler'), 'handle'];
    }
}