<?php

namespace CQRSModule\EventHandling\Listener;

use CQRS\Domain\Message\EventMessageInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class ServiceManagerEventListener implements ServiceManagerAwareInterface
{
    /** @var ServiceManager */
    private $serviceManager;

    private $eventHandlingServicesMap = [];

    public function __construct(array $params)
    {
    }

    /**
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function __invoke(EventMessageInterface $eventMessage)
    {
        $eventType = $eventMessage->getPayloadType();

        foreach ($this->eventHandlingServicesMap[$eventType] as $serviceName) {
            $service = $this->serviceManager->get($serviceName);
        }
    }
}
