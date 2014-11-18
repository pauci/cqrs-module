<?php

namespace CQRSModule\Options;

use Zend\Stdlib\AbstractOptions;

class Serializer extends AbstractOptions
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $instance;

    /**
     * @param string $class
     * @return $this
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
     * @param string $instance
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;
    }

    /**
     * @return string
     */
    public function getInstance()
    {
        return $this->instance;
    }
} 
