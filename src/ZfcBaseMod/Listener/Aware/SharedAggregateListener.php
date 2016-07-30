<?php
namespace ZfcBaseMod\Listener\Aware;

use Zend\EventManager\SharedEventManagerInterface;
trait SharedAggregateListener
{
    protected $listeners = array();
    
    public function detachShared(SharedEventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
}

