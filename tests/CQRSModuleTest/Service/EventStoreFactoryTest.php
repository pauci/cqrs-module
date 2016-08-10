<?php

namespace CQRSModuleTest\Service;

use CQRS\EventStore\ChainingEventStore;
use CQRS\EventStore\EventFilterInterface;
use CQRS\EventStore\FilteringEventStore;
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
                            'namespace'  => 'baz',
                            'size'       => 100
                        ],
                    ],
                ],
            ]
        );

        $serializer = $this->createMock(SerializerInterface::class);
        $serviceManager->setService('cqrs.serializer.bar', $serializer);

        $conn = new stdClass();
        $serviceManager->setService('cqrs.test.connection', $conn);

        /** @var FooEventStore $eventStore */
        $eventStore = $factory->createService($serviceManager);

        $this->assertInstanceOf(FooEventStore::class, $eventStore);

        $this->assertSame($serializer, $eventStore->serializer);
        $this->assertSame($conn, $eventStore->connection);
        $this->assertEquals('baz', $eventStore->namespace);
        $this->assertEquals(100, $eventStore->size);
    }

    public function testCreateEventStoreWithDefaults()
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

        $serializer = $this->createMock(SerializerInterface::class);
        $serviceManager->setService('cqrs.serializer.bar', $serializer);

        /** @var FooEventStore $eventStore */
        $eventStore = $factory->createService($serviceManager);

        $this->assertInstanceOf(FooEventStore::class, $eventStore);

        $this->assertSame($serializer, $eventStore->serializer);
        $this->assertNull($eventStore->connection);
        $this->assertNull($eventStore->namespace);
        $this->assertNull($eventStore->size);
    }

    public function testCreateChainingEventStore()
    {
        $factory        = new EventStoreFactory('chaining');
        $serviceManager = new ServiceManager();
        $serviceManager->setService(
            'Configuration',
            [
                'cqrs' => [
                    'event_store' => [
                        'chaining' => [
                            'class'        => ChainingEventStore::class,
                            'event_stores' => [
                                'foo'
                            ]
                        ]
                    ]
                ]
            ]
        );

        $eventStore = new FooEventStore();
        $serviceManager->setService('cqrs.event_store.foo', $eventStore);

        /** @var ChainingEventStore $eventStore */
        $chainingEventStore = $factory->createService($serviceManager);

        $this->assertInstanceOf(ChainingEventStore::class, $chainingEventStore);
        $this->assertAttributeContains($eventStore, 'eventStores', $chainingEventStore);
    }


    public function testCreateFilteringEventStore()
    {
        $factory        = new EventStoreFactory('filtering');
        $serviceManager = new ServiceManager();
        $serviceManager->setService(
            'Configuration',
            [
                'cqrs' => [
                    'event_store' => [
                        'filtering' => [
                            'class'        => FilteringEventStore::class,
                            'event_store'  => 'foo',
                            'event_filter' => 'some_event_filter'
                        ]
                    ]
                ]
            ]
        );

        $eventStore = new FooEventStore();
        $serviceManager->setService('cqrs.event_store.foo', $eventStore);

        $eventFilter = $this->createMock(EventFilterInterface::class);
        $serviceManager->setService('some_event_filter', $eventFilter);

        /** @var FilteringEventStore $eventStore */
        $filteringEventStore = $factory->createService($serviceManager);

        $this->assertInstanceOf(FilteringEventStore::class, $filteringEventStore);
        $this->assertAttributeSame($eventStore, 'eventStore', $filteringEventStore);
        $this->assertAttributeSame($eventFilter, 'filter', $filteringEventStore);
    }
}
