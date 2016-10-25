<?php

namespace CQRSModule\Service;

use CQRS\EventStore\ChainingEventStore;
use CQRS\EventStore\EventFilterInterface;
use CQRS\EventStore\EventStoreInterface;
use CQRS\EventStore\FilteringEventStore;
use CQRS\EventStore\MemoryEventStore;
use CQRS\Serializer\SerializerInterface;
use CQRSModule\Options\EventStore as EventStoreOptions;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventStoreFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return EventStoreInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EventStoreOptions $options */
        $options = $this->getOptions($container, 'event_store');
        return $this->create($container, $options);
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, EventStoreInterface::class);
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

        switch ($class) {
            case ChainingEventStore::class:
                $eventStores = [];
                foreach ($options->getEventStores() as $eventStore) {
                    $eventStores[] = $sl->get($eventStore);
                }
                return new ChainingEventStore($eventStores);

            case FilteringEventStore::class:
                /** @var EventStoreInterface $eventStore */
                $eventStore = $sl->get($options->getEventStore());
                /** @var EventFilterInterface $eventFilter */
                $eventFilter = $sl->get($options->getEventFilter());
                return new FilteringEventStore($eventStore, $eventFilter);

            case MemoryEventStore::class:
                return new MemoryEventStore();
        }

        /** @var SerializerInterface $serializer */
        $serializer = $sl->get($options->getSerializer());

        $connection = $options->getConnection();
        if ($connection) {
            $connection = $sl->get($connection);
        }

        $namespace = $options->getNamespace();
        $size      = $options->getSize();

        return new $class($serializer, $connection, $namespace, $size);
    }
} 
