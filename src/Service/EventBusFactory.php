<?php

namespace CQRSModule\Service;

use CQRS\EventHandling\EventBusInterface;
use CQRS\EventHandling\EventHandlerLocator;
use CQRS\HandlerResolver\ContainerHandlerResolver;
use CQRS\HandlerResolver\EventHandlerResolver;
use CQRSModule\Options\EventBus as EventBusOptions;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventBusFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $container
     * @param string $requestedName
     * @param array $options
     * @return EventBusInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EventBusOptions $options */
        $options = $this->getOptions($container, 'event_bus');
        return $this->create($container, $options);
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, EventBusInterface::class);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return EventBusOptions::class;
    }

    /**
     * @param ContainerInterface $container
     * @param EventBusOptions $options
     * @return EventBusInterface
     */
    protected function create(ContainerInterface $container, EventBusOptions $options)
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
                    $container,
                    new EventHandlerResolver()
                )
            ),
            $container->get($options->getLogger())
        );
    }
}
