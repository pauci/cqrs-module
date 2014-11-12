<?php

namespace CQRSModule\Service;

use CQRSModule\Options\Logger as LoggerOptions;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoggerFactory extends AbstractFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return SerializerInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var LoggerOptions $options */
        $options = $this->getOptions($serviceLocator, 'logger');
        return $this->create($options);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return LoggerOptions::class;
    }

    /**
     * @param LoggerOptions $options
     * @return SerializerInterface
     */
    protected function create(LoggerOptions $options)
    {
        $class = $options->getClass();

        return new $class;
    }
}
