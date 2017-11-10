<?php

namespace common\dispatchers;

/**
 * Interface EventDispatcher
 * @package common\dispatchers
 */
interface EventDispatcher
{
    public function dispatchAll(array $events);
    public function dispatch($event);
}