<?php

namespace CQRSModule\Options;

use CQRS\EventHandling\Publisher\SimpleIdentityMap;
use Zend\Stdlib\AbstractOptions;

class IdentityMap extends AbstractOptions
{
    /**
     * @var string
     */
    protected $class = SimpleIdentityMap::class;

    /**
     * @var string
     */
    protected $entityManager = 'doctrine.entitymanager.orm_default';

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
     * @param string $entityManager
     * @return self
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
