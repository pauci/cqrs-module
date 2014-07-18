<?php

namespace CQRSModule\Service;

use CQRS\EventHandling\EventBusInterface;
use CQRSModule\Options\EventBus as EventBusOptions;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventBusFactory extends AbstractFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return EventBusInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EventBusOptions $options */
        $options = $this->getOptions($serviceLocator, 'event_bus');
        return $this->create($serviceLocator, $options);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return EventBusOptions::class;
    }

    /**
     * @param ServiceLocatorInterface $sl
     * @param EventBusOptions $options
     * @return EventBusInterface
     */
    protected function create(ServiceLocatorInterface $sl, EventBusOptions $options)
    {
        $class = $options->getClass();

        /** @var \CQRS\EventHandling\Locator\EventHandlerLocatorInterface $eventHandlerLocator */
        $eventHandlerLocator = $sl->get($options->getEventHandlerLocator());

        /** @var \CQRS\EventStore\EventStoreInterface $eventStore */
        $eventStore = $sl->get($options->getEventStore());

        return new $class($eventHandlerLocator, $eventStore);
    }
} 