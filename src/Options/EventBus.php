<?php

namespace CQRSModule\Options;

use CQRS\EventHandling\SynchronousEventBus;
use Zend\Stdlib\AbstractOptions;

class EventBus extends AbstractOptions
{
    /**
     * @var string
     */
    protected $class = SynchronousEventBus::class;

    /**
     * @var array
     */
    protected $events = [];

    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * @var string
     */
    protected $eventHandlerLocator = 'cqrs_default';

    /**
     * @var string
     */
    protected $logger = 'cqrs.logger.cqrs_default';

    /**
     * @param string $class
     * @return self
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param array $events
     * @return $this
     */
    public function setEvents(array $events)
    {
        $this->events = $events;
        return $this;
    }

    /**
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param array $handlers
     * @return $this
     */
    public function setHandlers($handlers)
    {
        $this->handlers = $handlers;
        return $this;
    }

    /**
     * @return array
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * @param string $eventHandlerLocator
     * @return self
     */
    public function setEventHandlerLocator($eventHandlerLocator)
    {
        $this->eventHandlerLocator = $eventHandlerLocator;
        return $this;
    }

    /**
     * @return string
     */
    public function getEventHandlerLocator()
    {
        return "cqrs.event_handler_locator.{$this->eventHandlerLocator}";
    }

    /**
     * @param string $logger
     * @return self
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
