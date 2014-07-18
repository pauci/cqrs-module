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
        $this->dispatch('/cqrs/notifications');

        $this->assertResponseStatusCode(200);

        $this->assertModuleName('CQRSModule');
        $this->assertControllerName('CQRSModule\Controller\Notification');
        $this->assertControllerClass('NotificationController');
        $this->assertMatchedRouteName('cqrs/notifications');
    }
} 
