<?php

namespace CQRSModuleTest;

use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;

/**
 * Utility used to retrieve a freshly bootstrapped application's service manager
 */
class ServiceManagerFactory
{
    /**
     * @var array
     */
    protected static $config = [];

    /**
     * @param array $config
     */
    public static function setConfig(array $config)
    {
        static::$config = $config;
    }

    /**
     * Builds a new service manager
     *
     * @return ServiceManager
     */
    public static function getServiceManager()
    {
        $serviceManager = new ServiceManager();
        $config = new ServiceManagerConfig(
            array_key_exists('service_manager', static::$config) ? static::$config['service_manager'] : []
        );
        $config->configureServiceManager($serviceManager);
        $serviceManager->setService('ApplicationConfig', static::$config);

        /** @var \Zend\ModuleManager\ModuleManager $moduleManager */
        $moduleManager = $serviceManager->get('ModuleManager');
        $moduleManager->loadModules();

        return $serviceManager;
    }
}
