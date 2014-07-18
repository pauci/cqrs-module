<?php

namespace CQRSModule\Controller;

use CQRS\EventStore\EventStoreInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class NotificationController extends AbstractRestfulController
{
    public function __construct(EventStoreInterface $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function getList()
    {
        //$data = $this->eventStore->readPage();
        $data = [];

        return new JsonModel($data);
    }
} 
