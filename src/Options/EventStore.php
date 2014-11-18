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
     * @return self
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
}
