<?php

namespace CQRSModule\Service;

use CQRSModule\Controller\NotificationController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NotificationControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return NotificationController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $eventStore = $serviceLocator->getServiceLocator()->get('cqrs.event_store.cqrs_default');

        return new NotificationController($eventStore);
    }
}
