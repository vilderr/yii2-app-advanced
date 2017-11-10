<?php

namespace common\jobs;

use common\dispatchers\EventDispatcher;

/**
 * Class AsyncEventJobHandler
 * @package common\jobs
 */
class AsyncEventJobHandler
{
    private $_dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->_dispatcher = $dispatcher;
    }

    public function handle(AsyncEventJob $job)
    {
        $this->_dispatcher->dispatch($job->event);
    }
}