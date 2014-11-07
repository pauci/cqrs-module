<?php

namespace CQRSModule\Service;

use CQRS\EventHandling\EventBusInterface;
use CQRS\EventHandling\Publisher\DomainEventQueue;
use CQRS\EventHandling\Publisher\EventPublisherInterface;
use CQRS\EventHandling\Publisher\IdentityMapInterface;
use CQRS\EventStore\EventStoreInterface;
use CQRS\Plugin\Doctrine\EventHandling\Publisher\DoctrineEventPublisher;
use CQRSModule\Options\Logger as LoggerOptions;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoggerFactory extends AbstractFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return SerializerInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var LoggerOptions $options */
        $options = $this->getOptions($serviceLocator, 'serializer');
        return $this->create($serviceLocator, $options);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return LoggerOptions::class;
    }

    /**
     * @param ServiceLocatorInterface $sl
     * @param LoggerOptions $options
     * @return SerializerInterface
     */
    protected function create(ServiceLocatorInterface $sl, LoggerOptions $options)
    {
        $class = $options->getClass();

        return new $class;
    }
}
