<?php

namespace CQRSModule\Service;

use CQRS\EventStore\EventStoreInterface;
use CQRS\Plugin\Doctrine\EventStore\TableEventStore;
use CQRSModule\Options\EventStore as EventStoreOptions;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventStoreFactory extends AbstractFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return EventStoreInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EventStoreOptions $options */
        $options = $this->getOptions($serviceLocator, 'event_store');
        return $this->create($serviceLocator, $options);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return EventStoreOptions::class;
    }

    /**
     * @param ServiceLocatorInterface $sl
     * @param EventStoreOptions $options
     * @return EventStoreInterface
     */
    protected function create(ServiceLocatorInterface $sl, EventStoreOptions $options)
    {
        $class = $options->getClass();

        /** @var \CQRS\Serializer\SerializerInterface $serializer */
        $serializer = $sl->get($options->getSerializer());

        $namespace = $options->getNamespace();

        $connection = null;
        if ($options->getConnection()) {
            $connection = $sl->get($options->getConnection());
        }

        return new $class($serializer, $namespace, $connection);
    }
} 
