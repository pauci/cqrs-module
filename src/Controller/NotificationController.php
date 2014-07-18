<?php

namespace CQRSModule\Controller;

use CQRS\EventStore\EventStoreInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class NotificationController extends AbstractRestfulController
{
    /** @var EventStoreInterface */
    private $eventStore;

    /**
     * @param EventStoreInterface $eventStore
     */
    public function __construct(EventStoreInterface $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    /**
     * @return JsonModel
     */
    public function getList()
    {
        $events = $this->eventStore->read();

        $selfUrl = $this->url()
            ->fromRoute(null, [], [
                'query'           => $this->params()->fromQuery(),
                'force_canonical' => true
            ]);

        $data = [
            '_links' => [
                'self' => $selfUrl,
            ],
            'count' => count($events),
            '_embedded' => [
                'event' => array_values($events)
            ]
        ];

        return new JsonModel($data);
    }
} 
