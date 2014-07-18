<?php

namespace CQRSModuleTest\Service;

use CQRS\Domain\Message\DomainEventInterface;
use CQRS\EventStore\EventStoreInterface;
use CQRSModule\Service\EventStoreFactory;
use PHPUnit_Framework_TestCase;
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
                            'serializer' => 'bar'
                        ],
                    ],
                ],
            ]
        );

        $serializer = $this->getMock('CQRS\Serializer\SerializerInterface');
        $serviceManager->setService('cqrs.serializer.bar', $serializer);

        $service = $factory->createService($serviceManager);

        $this->assertInstanceOf(FooEventStore::class, $service);
    }
}

class FooEventStore implements EventStoreInterface
{
    public function store(DomainEventInterface $event)
    {}
}
