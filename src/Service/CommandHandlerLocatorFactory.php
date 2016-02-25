<?php

namespace CQRSModule\Service;

use CQRS\CommandHandling\Locator\ContainerCommandHandlerLocator;
use CQRSModule\Options\CommandHandlerLocator as CommandHandlerLocatorOptions;
use RuntimeException;
use Zend\Memory\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CommandHandlerLocatorFactory extends AbstractFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ContainerInterface
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
     * @return ContainerInterface
     * @throws RuntimeException
     */
    protected function create(ServiceLocatorInterface $sl, CommandHandlerLocatorOptions $options)
    {
        $map = [];
        foreach ($options->getHandlers() as $commandTypeOrServiceName => $serviceNameOrCommandTypes) {
            if (is_array($serviceNameOrCommandTypes)) {
                $commandTypes = $serviceNameOrCommandTypes;
                $serviceName = $commandTypeOrServiceName;

                foreach ($commandTypes as $commandType) {
                    $map[$commandType] = $serviceName;
                }
            } else {
                $commandType = $commandTypeOrServiceName;
                $serviceName = $serviceNameOrCommandTypes;

                $map[$commandType] = $serviceName;
            }
        }

        return new ContainerCommandHandlerLocator($sl, $map);
    }
}
