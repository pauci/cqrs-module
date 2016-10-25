<?php

namespace CQRSModule\Service;

use CQRS\CommandHandling\TransactionManager\TransactionManagerInterface;
use CQRS\Plugin\Doctrine\CommandHandling\AbstractOrmTransactionManager;
use CQRSModule\Options\TransactionManager as TransactionManagerOptions;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TransactionManagerFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return TransactionManagerInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var TransactionManagerOptions $options */
        $options = $this->getOptions($container, 'transaction_manager');
        return $this->create($container, $options);
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return TransactionManagerOptions::class;
    }

    /**
     * @param ContainerInterface $container
     * @param TransactionManagerOptions $options
     * @return TransactionManagerInterface
     */
    protected function create(ContainerInterface $container, TransactionManagerOptions $options)
    {
        $class = $options->getClass();

        if (is_subclass_of($class, AbstractOrmTransactionManager::class)) {
            /** @var \Doctrine\ORM\EntityManagerInterface $connection */
            $connection = $container->get($options->getConnection());
            $transactionManager = new $class($connection);
        } else {
            $transactionManager = new $class;
        }

        return $transactionManager;
    }
} 
