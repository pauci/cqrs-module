<?php

namespace CQRSModule\Service;

use CQRS\CommandHandling\CommandBusInterface;
use CQRSModule\Options\CommandBus as CommandBusOptions;
use Zend\ServiceManager\ServiceLocatorInterface;

class CommandBusFactory extends AbstractFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return CommandBusInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var CommandBusOptions $options */
        $options = $this->getOptions($serviceLocator, 'command_bus');
        return $this->create($serviceLocator, $options);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return CommandBusOptions::class;
    }

    /**
     * @param ServiceLocatorInterface $sl
     * @param CommandBusOptions $options
     * @return CommandBusInterface
     */
    protected function create(ServiceLocatorInterface $sl, CommandBusOptions $options)
    {
        $class = $options->getClass();

        /** @var \CQRS\CommandHandling\Locator\CommandHandlerLocatorInterface $commandHandlerLocator */
        $commandHandlerLocator = $sl->get($options->getCommandHandlerLocator());

        /** @var \CQRS\CommandHandling\TransactionManager\TransactionManagerInterface $transactionManager */
        $transactionManager = $sl->get($options->getTransactionManager());

        /** @var \CQRS\EventHandling\Publisher\EventPublisherInterface $eventPublisher */
        $eventPublisher = $sl->get($options->getEventPublisher());

        return new $class($commandHandlerLocator, $transactionManager, $eventPublisher);
    }
}
