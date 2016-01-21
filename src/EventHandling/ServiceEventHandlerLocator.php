<?php

namespace CQRSModule\EventHandling;

use CQRS\EventHandling\Locator\MemoryEventHandlerLocator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class ServiceEventHandlerLocator extends MemoryEventHandlerLocator implements
    ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /** @var array */
    private $services = [];

    /** @var array */
    private $subscribedServices = [];

    /**
     * @param string $eventName
     * @return array
     */
    public function getEventHandlers($eventName)
    {
        $this->subscribeServices($eventName);

        return parent::getEventHandlers($eventName);
    }

    /**
     * Maps given event name(s) to the service(s) of given name(s)
     *
     * @param string|array $eventName
     * @param string|array $serviceName
     * @param int $priority
     */
    public function addService($eventName, $serviceName, $priority = 1)
    {
        $eventNames   = (array) $eventName;
        $serviceNames = (array) $serviceName;

        foreach ($eventNames as $eventName) {
            $eventName = strtolower($eventName);

            foreach ($serviceNames as $serviceName) {
                $this->services[$eventName][$priority][] = $serviceName;
            }
        }
    }

    /**
     * @param string $eventName
     */
    private function subscribeServices($eventName)
    {
        $methodName = 'on' . $eventName;

        $eventName = strtolower($eventName);

        if (!isset($this->services[$eventName])) {
            return;
        }

        $listeners = [];

        foreach ($this->services[$eventName] as $priority => $serviceNames) {
            foreach ($serviceNames as $serviceName) {
                // Prevent multiple subscriptions of same service
                if (array_key_exists($serviceName, $this->subscribedServices)) {
                    continue;
                }

                $service = $this->serviceLocator->get($serviceName);

                if (method_exists($service, $methodName)) {
                    $listeners[$eventName][$priority] = [$service, $methodName];
                }


                $this->addSubscriber($service, $priority);
                $this->subscribedServices[$serviceName] = true;
            }
        }

        foreach ($listeners as $listener) {
            $this->addListener($eventName, $listener);
        }

        unset($this->services[$eventName]);
    }
}
