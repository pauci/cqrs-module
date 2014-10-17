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
use Zend\ServiceManager\ServiceLocatorInterface;

class EventPublisherFactory extends AbstractFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return EventPublisherInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EventPublisherOptions $options */
        $options = $this->getOptions($serviceLocator, 'event_publisher');
        return $this->create($serviceLocator, $options);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return EventPublisherOptions::class;
    }

    /**
     * @param ServiceLocatorInterface $sl
     * @param EventPublisherOptions $options
     * @return EventPublisherInterface
     */
    protected function create(ServiceLocatorInterface $sl, EventPublisherOptions $options)
    {
        $class = $options->getClass();

        /** @var EventBusInterface $eventBus */
        $eventBus = $sl->get($options->getEventBus());

        /** @var IdentityMapInterface $identityMap */
        $identityMap = $sl->get($options->getIdentityMap());

        /** @var EventStoreInterface $eventStore */
        $eventStore = $sl->get($options->getEventStore());

        $additionalMetadata = $options->getAdditionalMetadata();

        $eventPublisher = new $class($eventBus, new DomainEventQueue($identityMap), $eventStore, $additionalMetadata);

        if ($eventPublisher instanceof DoctrineEventPublisher) {
            /** @var EntityManager $entityManager */
            $entityManager = $sl->get($options->getEntityManager());
            $entityManager->getEventManager()->addEventSubscriber($eventPublisher);
        }

        return $eventPublisher;
    }
} 
