<?php

namespace CQRSModule\Service;

use CQRS\CommandHandling\CommandBusInterface;
use CQRS\CommandHandling\CommandHandlerLocator;
use CQRS\HandlerResolver\CommandHandlerResolver;
use CQRS\HandlerResolver\ContainerHandlerResolver;
use CQRSModule\Options\CommandBus as CommandBusOptions;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CommandBusFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return CommandBusInterface
     * @internal param ServiceLocatorInterface $serviceLocator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var CommandBusOptions $options */
        $options = $this->getOptions($container, 'command_bus');
        return $this->create($container, $options);
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, CommandBusInterface::class);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return CommandBusOptions::class;
    }

    /**
     * @param ContainerInterface $container
     * @param CommandBusOptions $options
     * @return CommandBusInterface
     */
    protected function create(ContainerInterface $container, CommandBusOptions $options)
    {
        $class = $options->getClass();

        $commandHandlers = $options->getCommands();
        foreach ($options->getHandlers() as $handler => $commands) {
            foreach ((array) $commands as $command) {
                $commandHandlers[$command] = $handler;
            }
        }

        return new $class(
            new CommandHandlerLocator(
                $commandHandlers,
                new ContainerHandlerResolver(
                    $container,
                    new CommandHandlerResolver()
                )
            ),
            $container->get($options->getTransactionManager()),
            $container->get($options->getEventPublisher()),
            $container->get($options->getLogger())
        );
    }
}
