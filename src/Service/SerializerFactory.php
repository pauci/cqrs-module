<?php

namespace CQRSModule\Service;

use CQRS\Serializer\SerializerInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use CQRSModule\Options\Serializer as SerializerOptions;

class SerializerFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return SerializerInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var SerializerOptions $options */
        $options = $this->getOptions($container, 'serializer');
        return $this->create($container, $options);
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, SerializerInterface::class);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return SerializerOptions::class;
    }

    /**
     * @param ContainerInterface $container
     * @param SerializerOptions $options
     * @return SerializerInterface
     */
    protected function create(ContainerInterface $container, SerializerOptions $options)
    {
        $class = $options->getClass();

        $instanceName = $options->getInstance();
        if ($instanceName !== null) {
            $instance = $container->get($instanceName);
            return new $class($instance);
        }

        return new $class;
    }
}
