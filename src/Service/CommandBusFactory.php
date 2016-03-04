<?php

namespace CQRSModule\Service;

use CQRS\CommandHandling\CommandBusInterface;
use CQRS\CommandHandling\CommandHandlerLocator;
use CQRS\HandlerResolver\CommandHandlerResolver;
use CQRS\HandlerResolver\ContainerHandlerResolver;
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

        $commandHandlers = $options->getCommands();
        foreach ($options->getHandlers() as $handler => $command) {
            $commandHandlers[$command] = $handler;
        }

        return new $class(
            new CommandHandlerLocator(
                $commandHandlers,
                new ContainerHandlerResolver(
                    $sl,
                    new CommandHandlerResolver()
                )
            ),
            $sl->get($options->getTransactionManager()),
            $sl->get($options->getEventPublisher()),
            $sl->get($options->getLogger())
        );
    }
}
