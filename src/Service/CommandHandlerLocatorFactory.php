<?php

namespace CQRSModule\Service;

use CQRS\CommandHandling\Locator\CommandHandlerLocatorInterface;
use CQRS\CommandHandling\Locator\MemoryCommandHandlerLocator;
use CQRSModule\Options\CommandHandlerLocator as CommandHandlerLocatorOptions;
use RuntimeException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CommandHandlerLocatorFactory extends AbstractFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return CommandHandlerLocatorInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var CommandHandlerLocatorOptions $options */
        $options = $this->getOptions($serviceLocator, 'command_handler_locator');
        return $this->create($serviceLocator, $options);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return CommandHandlerLocatorOptions::class;
    }

    /**
     * @param ServiceLocatorInterface $sl
     * @param CommandHandlerLocatorOptions $options
     * @return CommandHandlerLocatorInterface
     * @throws RuntimeException
     */
    protected function create(ServiceLocatorInterface $sl, CommandHandlerLocatorOptions $options)
    {
        $class = $options->getClass();

        if (!$class) {
            throw new RuntimeException('CommandHandlerLocatorInterface must have a class name to instantiate');
        }

        $commandHandlerLocator = new $class;

        if ($commandHandlerLocator instanceof ServiceLocatorAwareInterface) {
            $commandHandlerLocator->setServiceLocator($sl);
        }

        $handlers = $options->getHandlers();
        foreach ($handlers as $commandTypeOrServiceName => $serviceOrCommandTypes) {
            if (is_array($serviceOrCommandTypes)) {
                $commandTypes = $serviceOrCommandTypes;
                $service      = $commandTypeOrServiceName;

                foreach ($commandTypes as $commandType) {
                    $commandHandlerLocator->register($commandType, $service);
                }
            } else {
                $commandType = $commandTypeOrServiceName;
                $service     = $serviceOrCommandTypes;

                $commandHandlerLocator->register($commandType, $service);
            }
        }

        return $commandHandlerLocator;
    }
}
