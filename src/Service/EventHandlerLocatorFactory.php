<?php

namespace CQRSModule\Service;

use CQRS\EventHandling\Locator\EventHandlerLocatorInterface;
use CQRSModule\Options\EventHandlerLocator as EventHandlerLocatorOptions;
use RuntimeException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventHandlerLocatorFactory extends AbstractFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return EventHandlerLocatorInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EventHandlerLocatorOptions $options */
        $options = $this->getOptions($serviceLocator, 'event_handler_locator');
        return $this->create($serviceLocator, $options);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return EventHandlerLocatorOptions::class;
    }

    /**
     * @param ServiceLocatorInterface $sl
     * @param EventHandlerLocatorOptions $options
     * @return EventHandlerLocatorInterface
     * @throws RuntimeException
     */
    protected function create(ServiceLocatorInterface $sl, EventHandlerLocatorOptions $options)
    {
        $class = $options->getClass();

        if (!$class) {
            throw new RuntimeException('EventHandlerLocatorInterface must have a class name to instantiate');
        }

        /** @var EventHandlerLocatorInterface $eventHandlerLocator */
        $eventHandlerLocator = new $class;

        if ($eventHandlerLocator instanceof ServiceLocatorAwareInterface) {
            $eventHandlerLocator->setServiceLocator($sl);
        }

        $callbacks = $options->getCallbacks();

        foreach ($callbacks as $eventName => $callback) {
            $priority = 1;
            if (is_array($callback) && isset($callback['callback'])) {
                if (isset($callback['event'])) {
                    $eventName = $callback['event'];
                }
                if (isset($callback['priority'])) {
                    $priority = $callback['priority'];
                }
                $callback = $callback['callback'];
            }
            $eventHandlerLocator->registerCallback($eventName, $callback, $priority);
        }

        return $eventHandlerLocator;
    }
}
