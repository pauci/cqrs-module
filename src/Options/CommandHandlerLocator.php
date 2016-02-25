<?php

namespace CQRSModule\Options;

use CQRS\CommandHandling\Locator\ContainerCommandHandlerLocator;
use Zend\Stdlib\AbstractOptions;

class CommandHandlerLocator extends AbstractOptions
{
    /**
     * @var string
     */
    protected $class = ContainerCommandHandlerLocator::class;

    /**
     * @var array
     */
    protected $handlers = [];

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
     * @param object[] $handlers
     * @return self
     */
    public function setHandlers(array $handlers)
    {
        $this->handlers = $handlers;
        return $this;
    }

    /**
     * @return object[]
     */
    public function getHandlers()
    {
        return $this->handlers;
    }
}
