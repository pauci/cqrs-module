<?php

namespace CQRSModuleTest\Controller;

use CQRS\Domain\Message\GenericEventMessage;
use CQRSTest\Domain\Message\SomeEvent;
use Ramsey\Uuid\Uuid;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class NotificationsControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../configuration.php.dist'
        );

        parent::setUp();
    }

    public function testGetAll()
    {
        $messages = [];
        for ($i = 0; $i < 15; $i++) {
            $eventId = Uuid::fromString('4e95d633-7ffb-448e-8925-2d02996057a' . dechex($i));
            $messages[] = new GenericEventMessage(null, null, $eventId);
        }

        $eventStore = $this->getMock('CQRS\EventStore\EventStoreInterface');
        $eventStore->expects($this->once())
            ->method('iterate')
            ->willReturn($messages);

        $this->getApplicationServiceLocator()->setService('cqrs.event_store.cqrs_default', $eventStore);

        $this->dispatch('/cqrs/notifications');

        $this->assertResponseStatusCode(200);

        $this->assertModuleName('CQRSModule');
        $this->assertControllerName('CQRSModule\Controller\NotificationController');
        $this->assertControllerClass('NotificationController');
        $this->assertMatchedRouteName('cqrs/notifications');

        $result = json_decode($this->getResponse()->getContent(), true);

        $this->assertEquals([
            '_links' => [
                'self' => '/cqrs/notifications',
                'next' => '/cqrs/notifications?previousEventId=4e95d633-7ffb-448e-8925-2d02996057a9'
            ],
            'count' => 10,
            '_embedded' => [
                'event' => [
                    [],
                    [],
                    [],
                    [],
                    [],
                    [],
                    [],
                    [],
                    [],
                    [],
                ],
            ],
        ], $result);
    }
} 
