<?php

namespace CQRSModule\Service;

use CQRS\EventHandling\Publisher\IdentityMapInterface;
use CQRS\Plugin\Doctrine\EventHandling\Publisher\DoctrineIdentityMap;
use CQRSModule\Options\IdentityMap as IdentityMapOptions;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class IdentityMapFactory extends AbstractFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return IdentityMapInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var IdentityMapOptions $options */
        $options = $this->getOptions($serviceLocator, 'identity_map');
        return $this->create($serviceLocator, $options);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return IdentityMapOptions::class;
    }

    /**
     * @param ServiceLocatorInterface $sl
     * @param IdentityMapOptions $options
     * @return IdentityMapInterface
     */
    protected function create(ServiceLocatorInterface $sl, IdentityMapOptions $options)
    {
        $class = $options->getClass();

        if ($class == DoctrineIdentityMap::class) {
            /** @var EntityManager $entityManager */
            $entityManager = $sl->get($options->getEntityManager());
            return new DoctrineIdentityMap($entityManager);
        }

        return new $class;
    }
} 
