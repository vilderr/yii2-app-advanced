<?php

namespace common\dispatchers;

use Yii;
use yii\di\Container;

/**
 * Class SimpleEventDispatcher
 * @package common\dispatchers
 */
class SimpleEventDispatcher implements EventDispatcher
{
    private $_container;
    private $_listeners;

    public function __construct(Container $container, array $listeners)
    {
        $this->_container = $container;
        $this->_listeners = $listeners;
    }

    public function dispatchAll(array $events)
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    public function dispatch($event)
    {
        $eventName = get_class($event);
        if (array_key_exists($eventName, $this->_listeners)) {
            foreach ($this->_listeners[$eventName] as $listenerClass) {
                $listener = $this->resolveListener($listenerClass);
                $listener($event);
            }
        }
    }

    /**
     * @param $listenerClass
     *
     * @return callable
     */
    private function resolveListener($listenerClass)
    {
        return [$this->_container->get($listenerClass), 'handle'];
    }
}