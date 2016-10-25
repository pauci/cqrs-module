<?php

namespace CQRSModule\Service;

use CQRSModule\Controller\NotificationController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NotificationControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return NotificationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $eventStore = $container->get('cqrs.event_store.cqrs_default');

        return new NotificationController($eventStore);
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator->getServiceLocator(), NotificationController::class);
    }
}
