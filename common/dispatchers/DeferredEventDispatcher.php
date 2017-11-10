<?php

namespace common\dispatchers;

/**
 * Class DeferedEventDispatcher
 * @package common\dispatchers
 */
class DeferredEventDispatcher implements EventDispatcher
{
    private $_defer = false;
    private $_queue = [];
    private $_next;

    public function __construct(EventDispatcher $next)
    {
        $this->_next = $next;
    }

    public function dispatchAll(array $events)
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    public function dispatch($event)
    {
        if ($this->_defer) {
            $this->_queue[] = $event;
        } else {
            $this->_next->dispatch($event);
        }
    }

    public function defer()
    {
        $this->_defer = true;
    }

    public function clean()
    {
        $this->_queue = [];
        $this->_defer = false;
    }

    public function release()
    {
        foreach ($this->_queue as $i => $event) {
            $this->_next->dispatch($event);
            unset($this->_queue[$i]);
        }
        $this->defer = false;
    }
}