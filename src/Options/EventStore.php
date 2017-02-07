<?php

namespace CQRSModule\Options;

use Zend\Stdlib\AbstractOptions;

class EventStore extends AbstractOptions
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var array
     */
    protected $eventStores = [];

    /**
     * @var string
     */
    protected $eventFilter;

    /**
     * @var string
     */
    protected $serializer;

    /**
     * @var string
     */
    protected $connection;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var int
     */
    protected $size;

    /**
     * @param string $class
     * @return $this
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
     * @param array $eventStores
     * @return $this
     */
    public function setEventStores(array $eventStores)
    {
        $this->eventStores = $eventStores;
        return $this;
    }

    /**
     * @param string $eventStore
     * @return $this
     */
    public function setEventStore($eventStore)
    {
        return $this->setEventStores([$eventStore]);
    }

    /**
     * @return array
     */
    public function getEventStores()
    {
        return array_map(function ($eventStore) {
            return "cqrs.event_store.$eventStore";
        }, $this->eventStores);
    }

    /**
     * @return string
     */
    public function getEventStore()
    {
        $eventStores = $this->getEventStores();
        return isset($eventStores[0]) ? $eventStores[0] : null;
    }

    /**
     * @param string $eventFilter
     * @return $this
     */
    public function setEventFilter($eventFilter)
    {
        $this->eventFilter = $eventFilter;
        return $this;
    }

    /**
     * @return string
     */
    public function getEventFilter()
    {
        return $this->eventFilter;
    }

    /**
     * @param string $serializer
     * @return $this
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
        return $this;
    }

    /**
     * @return string
     */
    public function getSerializer()
    {
        return "cqrs.serializer.{$this->serializer}";
    }

    /**
     * @param string $connection
     * @return $this
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * @return string
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param string $namespace
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param int $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }
}
