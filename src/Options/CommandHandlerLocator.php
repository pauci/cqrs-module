<?php

namespace CQRSModule\Options;

use CQRSModule\CommandHandling\ServiceCommandHandlerLocator;
use Zend\Stdlib\AbstractOptions;

class CommandHandlerLocator extends AbstractOptions
{
    /** @var string */
    protected $class = ServiceCommandHandlerLocator::class;

    /** @var array */
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
     * @param array $handlers
     * @return self
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
}
