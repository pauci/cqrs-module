<?php

namespace CQRSModuleTest\Service;

use CQRS\Domain\Message\EventMessageInterface;
use CQRS\EventStore\EventStoreInterface;
use CQRS\Serializer\SerializerInterface;

class FooEventStore implements EventStoreInterface
{
    public $serializer;
    public $connection;
    public $namespace;
    public $size;

    public function __construct(SerializerInterface $serializer = null, $connection = null, $namespace = null, $size = null)
    {
        $this->serializer = $serializer;
        $this->connection = $connection;
        $this->namespace  = $namespace;
        $this->size       = $size;
    }

    public function store(EventMessageInterface $event)
    {}

    public function read($offset = null, $limit = 10)
    {}
}
