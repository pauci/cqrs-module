<?php

namespace CQRSModule\Controller;

use CQRS\Domain\Message\EventMessageInterface;
use CQRS\EventStore\EventStoreInterface;
use Ramsey\Uuid\Uuid;
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

        $lastEventId = $previousEventId;
        $events = [];
        $i = 0;
        /** @var EventMessageInterface $event */
        foreach ($iterator as $event) {
            if ($i >= $count) {
                break;
            }

            $events[] = $event;
            $i++;
            $lastEventId = $event->getId()->toString();
        }

        $nextUrl = $this->url()->fromRoute(null, [], [
            'force_canonical' => true,
            'query' => [
                'previousEventId' => (string)$lastEventId,
            ],
        ], true);

        $data = [
            '_links' => [
                'self' => $selfUrl,
                'next' => $nextUrl
            ],
            'count' => count($events),
            '_embedded' => [
                'event' => array_values($events),
            ],
        ];

        return new JsonModel($data);
    }
}
