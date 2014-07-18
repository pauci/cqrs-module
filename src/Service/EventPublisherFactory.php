<?php

namespace CQRSModule\Service;

use CQRS\EventHandling\Publisher\EventPublisherInterface;
use CQRS\Plugin\Doctrine\EventHandling\OrmDomainEventPublisher;
use CQRSModule\Options\EventPublisher as EventPublisherOptions;
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

        /** @var \CQRS\EventHandling\EventBusInterface $eventBus */
        $eventBus = $sl->get($options->getEventBus());

        $eventPublisher = new $class($eventBus);

        if ($eventPublisher instanceof OrmDomainEventPublisher) {
            /** @var \Doctrine\ORM\EntityManager $entityManager */
            $entityManager = $sl->get($options->getOrmEntityManager());
            $entityManager->getEventManager()->addEventSubscriber($eventPublisher);
        }

        return $eventPublisher;
    }
} 
