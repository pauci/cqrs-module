<?php

namespace CQRSModule\Options;

use CQRS\CommandHandling\TransactionManager\NoTransactionManager;
use Zend\Stdlib\AbstractOptions;

class TransactionManager extends AbstractOptions
{
    /**
     * @var string
     */
    protected $class = NoTransactionManager::class;

    /**
     * @var string
     */
    protected $connection;

    /**
     * @param string $class
     * @return self
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $connection
     * @return self
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * @return string
     */
    public function getConnection()
    {
        return $this->connection;
    }
} 
