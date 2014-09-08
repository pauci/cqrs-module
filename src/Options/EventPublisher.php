<?php

namespace CQRSModule\Options;

use CQRS\EventHandling\Publisher\SimpleEventPublisher;
use Zend\Stdlib\AbstractOptions;

class EventPublisher extends AbstractOptions
{
    /**
     * @var string
     */
    protected $class = SimpleEventPublisher::class;

    /**
     * @var string
     */
    protected $eventBus = 'cqrs_default';

    /**
     * @var string
     */
    protected $eventStore = 'cqrs_default';

    /**
     * @var string
     */
    protected $identityMap = 'cqrs_default';

    /**
     * @var array
     */
    protected $additionalMetadata;

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
     * @param string $eventBus
     * @return self
     */
    public function setEventBus($eventBus)
    {
        $this->eventBus = $eventBus;
        return $this;
    }

    /**
     * @return string
     */
    public function getEventBus()
    {
        return "cqrs.event_bus.{$this->eventBus}";
    }

    /**
     * @param $eventStore
     * @return self
     */
    public function setEventStore($eventStore)
    {
        $this->eventStore = $eventStore;
        return $this;
    }

    /**
     * @return string
     */
    public function getEventStore()
    {
        return "cqrs.event_store.{$this->eventStore}";
    }

    /**
     * @param string $identityMap
     * @return self
     */
    public function setIdentityMap($identityMap)
    {
        $this->identityMap = $identityMap;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentityMap()
    {
        return "cqrs.identity_map.{$this->identityMap}";
    }

    /**
     * @param array $additionalMetadata
     */
    public function setAdditionalMetadata(array $additionalMetadata)
    {
        $this->additionalMetadata = $additionalMetadata;
    }

    public function getAdditionalMetadata()
    {
        return $this->additionalMetadata;
    }
}
