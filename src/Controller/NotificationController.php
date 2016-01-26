<?php

namespace CQRSModule\Controller;

use CQRS\Domain\Message\EventMessageInterface;
use CQRS\EventStore\EventStoreInterface;
use Ramsey\Uuid\Uuid;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class NotificationController extends AbstractRestfulController
{
    /**
     * @var EventStoreInterface
     */
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
        $previousEventId = $this->params()->fromQuery('previousEventId');
        $count = $this->params()->fromQuery('count', 10);

        if ($previousEventId) {
            $previousEventId = Uuid::fromString($previousEventId);
        }

        $iterator = $this->eventStore->iterate($previousEventId);

        $selfUrl = $this->url()
            ->fromRoute(null, [], ['force_canonical' => true], true);
        $nextUrl = false;

        $events = [];
        $i = 0;
        /** @var EventMessageInterface $event */
        foreach ($iterator as $event) {
            $events[] = $event;
            $i++;
            if ($i >= $count) {
                $nextUrl = $this->url()->fromRoute(null, [], [
                    'force_canonical' => true,
                    'query' => [
                        'previousEventId' => $event->getId()->toString(),
                    ],
                ], true);
                break;
            }
        }


        $data = [
            '_links' => [
                'self' => $selfUrl,
            ],
            'count' => count($events),
            '_embedded' => [
                'event' => array_values($events),
            ],
        ];

        if ($nextUrl) {
            $data['_links']['next'] = $nextUrl;
        }

        return new JsonModel($data);
    }
} 
