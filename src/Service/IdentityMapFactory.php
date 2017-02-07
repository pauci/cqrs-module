<?php

namespace CQRSModule\Service;

use CQRS\EventHandling\Publisher\IdentityMapInterface;
use CQRS\Plugin\Doctrine\EventHandling\Publisher\DoctrineIdentityMap;
use CQRSModule\Options\IdentityMap as IdentityMapOptions;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IdentityMapFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return IdentityMapInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var IdentityMapOptions $options */
        $options = $this->getOptions($container, 'identity_map');
        return $this->create($container, $options);
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, IdentityMapInterface::class);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return IdentityMapOptions::class;
    }

    /**
     * @param ContainerInterface $container
     * @param IdentityMapOptions $options
     * @return IdentityMapInterface
     */
    protected function create(ContainerInterface $container, IdentityMapOptions $options)
    {
        $class = $options->getClass();

        if ($class == DoctrineIdentityMap::class) {
            /** @var EntityManager $entityManager */
            $entityManager = $container->get($options->getEntityManager());
            return new DoctrineIdentityMap($entityManager);
        }

        return new $class;
    }
}
