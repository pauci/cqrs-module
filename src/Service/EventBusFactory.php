<?php

namespace CQRSModule\Service;

use CQRS\EventHandling\EventBusInterface;
use CQRS\EventHandling\EventHandlerLocator;
use CQRS\HandlerResolver\ContainerHandlerResolver;
use CQRS\HandlerResolver\EventHandlerResolver;
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

        $events = $options->getEvents();
        foreach ($options->getHandlers() as $handler => $handlerEvents) {
            foreach ((array) $handlerEvents as $event) {
                $events[$event][] = $handler;
            }
        }

        return new $class(
            new EventHandlerLocator(
                $events,
                new ContainerHandlerResolver(
                    $sl,
                    new EventHandlerResolver()
                )
            ),
            $sl->get($options->getLogger())
        );
    }
} 
