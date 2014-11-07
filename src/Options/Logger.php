<?php

namespace CQRSModule\Options;

use Zend\Stdlib\AbstractOptions;

class Logger extends AbstractOptions
{
    /** @var string */
    protected $class;

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
}
