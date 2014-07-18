<?php

namespace CQRSModuleTest\ServiceFactory;

use CQRS\Serializer\ReflectionSerializer;
use CQRSModuleTest\ServiceManagerFactory;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class ModuleDefinedServicesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceManager;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->serviceManager = ServiceManagerFactory::getServiceManager();
    }

    /**
     * Verifies that the module defines the correct services
     *
     * @dataProvider getServicesThatShouldBeDefined
     */
    public function testModuleDefinedServices($serviceName, $defined)
    {
        $this->assertSame($defined, $this->serviceManager->has($serviceName));
    }

    /**
     * Verifies that the module defines the correct services
     *
     * @dataProvider getServicesThatCanBeFetched
     */
    public function testModuleFetchedService($serviceName, $expectedClass)
    {
        $this->assertInstanceOf($expectedClass, $this->serviceManager->get($serviceName));
    }

    /**
     * Verifies that the module defines the correct services
     *
     * @dataProvider getServicesThatCannotBeFetched
     */
    public function testModuleInvalidService($serviceName)
    {
        $this->setExpectedException(ServiceNotFoundException::class);

        $this->serviceManager->get($serviceName);
    }

    /**
     * @return array
     */
    public function getServicesThatShouldBeDefined()
    {
        return [
            ['cqrs.command_bus.cqrs_default', true],
            ['cqrs.command_handler_locator.cqrs_default', true],
            ['cqrs.transaction_manager.cqrs_default', true],
            ['cqrs.event_publisher.cqrs_default', true],
            ['cqrs.event_bus.cqrs_default', true],
            ['cqrs.event_handler_locator.cqrs_default', true],
            ['cqrs.event_store.cqrs_default', true],
            ['cqrs.serializer.reflection', true],
            ['foo', false],
            ['foo.bar', false],
            ['foo.bar.baz', false],
            ['cqrs', false],
            ['cqrs.foo', false],
            ['cqrs.foo.bar', false],
            ['cqrs.command_bus.bar', false],
            //['cqrs.cache.zendcachestorage'],
        ];
    }

    /**
     * @return array
     */
    public function getServicesThatCanBeFetched()
    {
        return [
            ['cqrs.serializer.reflection', ReflectionSerializer::class],
        ];
    }

    /**
     * @return array
     */
    public function getServicesThatCannotBeFetched()
    {
        return [
            ['foo'],
            ['foo.bar'],
            ['foo.bar.baz'],
            ['cqrs'],
            ['cqrs.foo'],
            ['cqrs.foo.bar'],
            ['cqrs.command_bus.bar'],
        ];
    }
}
