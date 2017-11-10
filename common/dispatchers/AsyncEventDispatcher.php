<?php

namespace common\dispatchers;

use Yii;
use yii\queue\Queue;
use common\jobs\AsyncEventJob;

/**
 * Class AsyncEventDispatcher
 * @package common\dispatchers
 */
class AsyncEventDispatcher implements EventDispatcher
{
    private $_queue;

    public function __construct(Queue $queue)
    {
        $this->_queue = $queue;
    }

    public function dispatchAll(array $events)
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    public function dispatch($event)
    {
        $job = new AsyncEventJob($event);
        $this->_queue->push($job);
    }
}