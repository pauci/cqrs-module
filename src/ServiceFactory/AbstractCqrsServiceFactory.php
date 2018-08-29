<?php

namespace CQRSModule\ServiceFactory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\ServiceLocatorInterface;

class AbstractCqrsServiceFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return $this->has($container, $requestedName);
    }

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $this->has($serviceLocator, $requestedName);
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $this($serviceLocator, $requestedName);
    }

    /**
     * @param ContainerInterface $container
     * @param string $name
     * @return bool
     */
    public function has(ContainerInterface $container, $name)
    {
        return false !== $this->getFactoryMapping($container, $name);
    }

    /**
     * @param ContainerInterface $container
     * @param string $name
     * @param array $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $mappings = $this->getFactoryMapping($container, $name);

        if (!$mappings) {
            throw new ServiceNotFoundException();
        }

        $factoryClass = $mappings['factoryClass'];
        /* @var $factory \DoctrineModule\Service\AbstractFactory */
        $factory = new $factoryClass($mappings['serviceName']);

        return $factory->createService($container);
    }

    /**
     * @param ContainerInterface $serviceLocator
     * @param string $name
     * @return bool|array
     */
    private function getFactoryMapping(ContainerInterface $serviceLocator, $name)
    {
        $matches = [];

        if (!preg_match('/^cqrs\.(?P<serviceType>[a-z0-9_]+)\.(?P<serviceName>[a-z0-9_]+)$/i', $name, $matches)) {
            return false;
        }

        $config      = $serviceLocator->get('Config');
        $serviceType = $matches['serviceType'];
        $serviceName = $matches['serviceName'];

        if (!isset($config['cqrs_factories'][$serviceType])
            || !isset($config['cqrs'][$serviceType][$serviceName])
        ) {
            return false;
        }

        return [
            'serviceType'  => $serviceType,
            'serviceName'  => $serviceName,
            'factoryClass' => $config['cqrs_factories'][$serviceType],
        ];
    }
}
