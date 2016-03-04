<?php

namespace CQRSModule\Options;

use CQRS\CommandHandling\SequentialCommandBus;
use Zend\Stdlib\AbstractOptions;

class CommandBus extends AbstractOptions
{
    /**
     * @var string
     */
    protected $class = SequentialCommandBus::class;

    /**
     * @var array
     */
    protected $commands = [];

    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * @var string
     */
    protected $transactionManager = 'cqrs_default';

    /**
     * @var string
     */
    protected $eventPublisher = 'cqrs_default';

    /**
     * @var string
     */
    protected $logger = 'cqrs.logger.cqrs_default';

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
     * @param array $commands
     * @return $this
     */
    public function setCommands(array $commands)
    {
        $this->commands = $commands;
        return $this;
    }

    /**
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param array $handlers
     * @return $this
     */
    public function setHandlers(array $handlers)
    {
        $this->handlers = $handlers;
        return $this;
    }

    /**
     * @return array
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * @param string $transactionManager
     * @return self
     */
    public function setTransactionManager($transactionManager)
    {
        $this->transactionManager = $transactionManager;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionManager()
    {
        return "cqrs.transaction_manager.{$this->transactionManager}";
    }

    /**
     * @param string $eventPublisher
     * @return self
     */
    public function setEventPublisher($eventPublisher)
    {
        $this->eventPublisher = $eventPublisher;
        return $this;
    }

    /**
     * @return string
     */
    public function getEventPublisher()
    {
        return "cqrs.event_publisher.{$this->eventPublisher}";
    }

    /**
     * @param string $logger
     * @return self
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogger()
    {
        return $this->logger;
    }
} 
