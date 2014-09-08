<?php

namespace CQRSModuleTest\Service;

use CQRS\Domain\Message\EventMessageInterface;
use CQRS\EventStore\EventStoreInterface;
use CQRS\Serializer\SerializerInterface;
use CQRSModule\Service\EventStoreFactory;
use PHPUnit_Framework_TestCase;
use stdClass;
use Zend\ServiceManager\ServiceManager;

class EventStoreFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreateEventStore()
    {
        $factory        = new EventStoreFactory('foo');
        $serviceManager = new ServiceManager();
        $serviceManager->setService(
            'Configuration',
            [
                'cqrs' => [
                    'event_store' => [
                        'foo' => [
                            'class'      => FooEventStore::class,
                            'serializer' => 'bar',
                            'connection' => 'cqrs.test.connection',
                            'namespace'  => 'baz'
                        ],
                    ],
                ],
            ]
        );

        $serializer = $this->getMock('CQRS\Serializer\SerializerInterface');
        $serviceManager->setService('cqrs.serializer.bar', $serializer);

        $conn = new stdClass();
        $serviceManager->setService('cqrs.test.connection', $conn);

        /** @var FooEventStore $eventStore */
        $eventStore = $factory->createService($serviceManager);

        $this->assertInstanceOf(FooEventStore::class, $eventStore);

        $this->assertSame($serializer, $eventStore->serializer);
        $this->assertSame($conn, $eventStore->connection);
        $this->assertEquals('baz', $eventStore->namespace);
    }

    public function testCreateDefaultEventStore()
    {
        $factory        = new EventStoreFactory('foo');
        $serviceManager = new ServiceManager();
        $serviceManager->setService(
            'Configuration',
            [
                'cqrs' => [
                    'event_store' => [
                        'foo' => [
                            'class'      => FooEventStore::class,
                            'serializer' => 'bar',
                        ],
                    ],
                ],
            ]
        );

        $serializer = $this->getMock('CQRS\Serializer\SerializerInterface');
        $serviceManager->setService('cqrs.serializer.bar', $serializer);

        /** @var FooEventStore $eventStore */
        $eventStore = $factory->createService($serviceManager);

        $this->assertInstanceOf(FooEventStore::class, $eventStore);

        $this->assertSame($serializer, $eventStore->serializer);
        $this->assertNull($eventStore->connection);
        $this->assertEquals('default', $eventStore->namespace);
    }
}

class FooEventStore implements EventStoreInterface
{
    public $serializer;
    public $connection;
    public $namespace;

    public function __construct(SerializerInterface $serializer, $connection, $namespace = 'default')
    {
        $this->serializer = $serializer;
        $this->connection = $connection;
        $this->namespace  = $namespace;
    }

    public function store(EventMessageInterface $event)
    {}

    public function read($offset = null, $limit = 10)
    {}
}
