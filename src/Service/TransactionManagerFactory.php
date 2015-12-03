<?php

namespace CQRSModule\Service;

use CQRS\CommandHandling\TransactionManager\TransactionManagerInterface;
use CQRS\Plugin\Doctrine\CommandHandling\AbstractOrmTransactionManager;
use CQRSModule\Options\TransactionManager as TransactionManagerOptions;
use Zend\ServiceManager\ServiceLocatorInterface;

class TransactionManagerFactory extends AbstractFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return TransactionManagerInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var TransactionManagerOptions $options */
        $options = $this->getOptions($serviceLocator, 'transaction_manager');
        return $this->create($serviceLocator, $options);
    }

    /**
     * @return string
     */
    public function getOptionsClass()
    {
        return TransactionManagerOptions::class;
    }

    /**
     * @param ServiceLocatorInterface $sl
     * @param TransactionManagerOptions $options
     * @return TransactionManagerInterface
     */
    protected function create(ServiceLocatorInterface $sl, TransactionManagerOptions $options)
    {
        $class = $options->getClass();

        if (is_subclass_of($class, AbstractOrmTransactionManager::class)) {
            /** @var \Doctrine\ORM\EntityManagerInterface $connection */
            $connection = $sl->get($options->getConnection());
            $transactionManager = new $class($connection);
        } else {
            $transactionManager = new $class;
        }

        return $transactionManager;
    }
} 
