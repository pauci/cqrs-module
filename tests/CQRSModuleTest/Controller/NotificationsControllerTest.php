<?php

namespace CQRSModuleTest\Controller;

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
        $eventStore = $this->getMock('CQRS\EventStore\EventStoreInterface');
        $eventStore->expects($this->once())
            ->method('read')
            ->willReturn([
                1 => ['id' => 'a'],
                2 => ['id' => 'b']
            ]);

        $this->getApplicationServiceLocator()->setService('cqrs.event_store.cqrs_default', $eventStore);

        $this->dispatch('/cqrs/notifications');

        $this->assertResponseStatusCode(200);

        $this->assertModuleName('CQRSModule');
        $this->assertControllerName('CQRSModule\Controller\Notification');
        $this->assertControllerClass('NotificationController');
        $this->assertMatchedRouteName('cqrs/notifications');

        $this->assertEquals('{"_links":{"self":"\/cqrs\/notifications"},"count":2,"_embedded":{"event":[{"id":"a"},{"id":"b"}]}}', $this->getResponse()->getContent());
    }
} 
