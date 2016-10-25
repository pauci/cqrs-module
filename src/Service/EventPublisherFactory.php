<?php

namespace CQRSModule\Service;

use CQRS\EventHandling\EventBusInterface;
use CQRS\EventHandling\Publisher\DomainEventQueue;
use CQRS\EventHandling\Publisher\EventPublisherInterface;
use CQRS\EventHandling\Publisher\IdentityMapInterface;
use CQRS\EventStore\EventStoreInterface;
use CQRS\Plugin\Doctrine\EventHandling\Publisher\DoctrineEventPublisher;
use CQRSModule\Options\EventPublisher as EventPublisherOptions;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventPublisherFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return EventPublisherInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EventPublisherOptions $options */
        $options = $this->getOptions($container, 'event_publisher');
        return $this->create($container, $options);
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, EventPublisherInterface::class);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return EventPublisherOptions::class;
    }

    /**
     * @param ContainerInterface $container
     * @param EventPublisherOptions $options
     * @return EventPublisherInterface
     */
    protected function create(ContainerInterface $container, EventPublisherOptions $options)
    {
        $class = $options->getClass();

        /** @var EventBusInterface $eventBus */
        $eventBus = $container->get($options->getEventBus());

        /** @var IdentityMapInterface $identityMap */
        $identityMap = $container->get($options->getIdentityMap());

        /** @var EventStoreInterface $eventStore */
        $eventStore = $container->get($options->getEventStore());

        $additionalMetadata = $options->getAdditionalMetadata();

        $eventPublisher = new $class($eventBus, new DomainEventQueue($identityMap), $eventStore, $additionalMetadata);

        if ($eventPublisher instanceof DoctrineEventPublisher) {
            /** @var EntityManager $entityManager */
            $entityManager = $container->get($options->getEntityManager());
            $entityManager->getEventManager()->addEventSubscriber($eventPublisher);
        }

        return $eventPublisher;
    }
} 
