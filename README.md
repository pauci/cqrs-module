# Sygic CQRS Module for Zend Framework 2

Sygic CQRSModule integrates CQRS library with Zend Framework 2 quickly and easily.

## Installation

Installation of this module uses composer. For composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

First ensure you have sygic's local satis repository added to your composer.json:

    "repositories": [
        {
            "type": "composer",
            "url": "http://satis.ssd.sygic.local"
        }
    ],

Install the module via command:

    php composer.phar require sygic/cqrs-module
    # (When asked for a version, type `dev-master`)

Then add `CQRSModule` to your `config/application.config.php`

## Setup
```php
return [
    'cqrs' => [
        'commandHandlerLocator' => [
            'cqrs_default' => [
                'handlers' => [
                    'UserService' => [
                        'ChangeUserName'
                    ]
                ]
            ]
        ],
        'eventHandlerLocator' => [
            'cqrs_default' => [
                'services' => [
                    'EchoEventListener' => [
                        'UserNameChanged'
                    ]
                ]
            ]
        ]
    ]
];
```

## Registered Service names

 * `cqrs.command_bus.cqrs_default`: a `CQRS\CommandHandling\CommandBusInterface` instance
 * `cqrs.command_handler_locator.cqrs_default`: a `CQRS\CommandHandling\Locator\CommandHandlerLocatorInterface` instance
 * `cqrs.transaction_manager.cqrs_default`: a `CQRS\CommandHandling\TransactionManager\TransactionManagerInterface` instance
 * `cqrs.event_publisher.cqrs_default`: the `CQRS\EventHandling\Publisher\EventPublisherInterface` instance
 * `cqrs.event_bus.cqrs_default`: the `CQRS\EventHandling\EventBusInterface` instance
 * `cqrs.event_handler_locator.cqrs_default`: the `CQRS\EventHandling\Locator\EventHandlerLocatorInterface` instance
 * `cqrs.event_store.cqrs_default`: the `CQRS\EventStore\EventStoreInterface` instance
 * `cqrs.serializer.reflection`: the `CQRS\Serializer\ReflectionSerializer` instance

#### Service Locator
To access the entity manager, use the main service locator:

```php
// for example, in a controller:
$commandBus          = $this->getServiceLocator()->get('cqrs.command_bus.cqrs_default');
$eventHandlerLocator = $this->getServiceLocator()->get('cqrs.event_handler_locator.cqrs_default');
```
