<?php

namespace CQRSModule\Service;

use CQRS\Serializer\SerializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use CQRSModule\Options\Serializer as SerializerOptions;

class SerializerFactory extends AbstractFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return SerializerInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var SerializerOptions $options */
        $options = $this->getOptions($serviceLocator, 'serializer');
        return $this->create($serviceLocator, $options);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return SerializerOptions::class;
    }

    /**
     * @param ServiceLocatorInterface $sl
     * @param SerializerOptions $options
     * @return SerializerInterface
     */
    protected function create(ServiceLocatorInterface $sl, SerializerOptions $options)
    {
        $class = $options->getClass();

        return new $class;
    }
}
