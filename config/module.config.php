<?php

return [
    'cqrs' => [
        'command_bus' => [
            'cqrs_default' => [
                /*
                'class'                   => CQRS\CommandHandling\SequentialCommandBus::class,
                'command_handler_locator' => 'cqrs_default',
                'transaction_manager'     => 'cqrs_default',
                'event_publisher'         => 'cqrs_default',
                 */
            ],
        ],

        'command_handler_locator' => [
            'cqrs_default' => [
                'class'    => CQRSModule\CommandHandling\ServiceCommandHandlerLocator::class,
                /*
                'handlers' => [
                    '<CommandType>' => '<CommandHandlerServiceName>',

                    '<CommandHandlerServiceName>' => [
                        '<CommandType1>',
                        '<CommandType2>',
                    ]
                ],
                 */
            ],
        ],

        'transaction_manager' => [
            'cqrs_default' => [
                /*
                // Configuration to use with doctrine:
                'class'      => 'CQRS\Plugin\Doctrine\CommandHandling\ImplicitOrmTransactionManager',
                'connection' => 'doctrine.entitymanager.orm_default',
                 */
            ],
        ],

        'event_publisher' => [
            'cqrs_default' => [
                /*
                'class'       => CQRS\EventHandling\Publisher\SimpleEventPublisher::class,
                'event_bus'   => 'cqrs_default',
                'event_store' => 'cqrs_default',
                 */
            ],
        ],

        'identity_map' => [
            'cqrs_default' => [],
        ],

        'event_bus' => [
            'cqrs_default' => [
                /*
                'class'                 => CQRS\EventHandling\SynchronousEventBus::class,
                'event_handler_locator' => 'cqrs_default',
                'event_store'           => 'cqrs_default',
                'listeners' => [
                    '<EventName>' => [
                        'class'    => CallbackEventHandler::class,
                        'callback' => function($event, $metadata, $timestamp, $sequenceNumber, $aggregateId) {}
                    ],

                    [
                        'type'    => 'ServiceMethodsEventHandler',
                        'service' => $service,
                        'method'  => ''
                    ]
                ],
                 */
            ],
        ],

        'event_handler_locator' => [
            'cqrs_default' => [
                'class'    => CQRSModule\EventHandling\ServiceEventHandlerLocator::class,
                /*
                'services' => [
                    '<ServiceName>' => [
                        '<EventName1>',
                        '<EventName2>',
                    ],

                    [
                        'event'    => ['<EventName1>', ...],
                        'service'  => ['<ServiceName1', ...],
                        'priority' => 1
                    ],
                ],
                'callbacks' => [
                    '<EventName>' => function($event) {},

                    [
                        'event'    => ['<EventName1>, '<EventName2'],
                        'callback' => function($event) {},
                        'priority' => 1
                    ]
                ],
                'subscribers' => [
                    // An array of instances
                ],
                 */
            ],
        ],

        'event_store' => [
            'cqrs_default' => [
                'class'      => CQRS\Plugin\Doctrine\EventStore\TableEventStore::class,
                'connection' => 'doctrine.connection.orm_default',
                'serializer' => 'reflection',
            ],
        ],

        'serializer' => [
            'reflection' => [
                'class' => CQRS\Serializer\ReflectionSerializer::class,
            ],
        ],
    ],

    'cqrs_factories' => [
        'command_bus'             => CQRSModule\Service\CommandBusFactory::class,
        'command_handler_locator' => CQRSModule\Service\CommandHandlerLocatorFactory::class,
        'transaction_manager'     => CQRSModule\Service\TransactionManagerFactory::class,
        'event_publisher'         => CQRSModule\Service\EventPublisherFactory::class,
        'event_bus'               => CQRSModule\Service\EventBusFactory::class,
        'identity_map'            => CQRSModule\Service\IdentityMapFactory::class,
        'event_handler_locator'   => CQRSModule\Service\EventHandlerLocatorFactory::class,
        'event_store'             => CQRSModule\Service\EventStoreFactory::class,
        'serializer'              => CQRSModule\Service\SerializerFactory::class,
    ],

    'service_manager' => [
        'abstract_factories' => [
            'CQRS' => CQRSModule\ServiceFactory\AbstractCqrsServiceFactory::class,
        ],
        'invokables' => [
            'cqrs.logger.cqrs_default' => Psr\Log\NullLogger::class,
        ],
    ],

    'doctrine' => [
        'driver' => [
            'CQRS_driver' => [
                'class' => Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../../cqrs/src/Domain/Model',
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            CQRSModule\Controller\NotificationController::class => CQRSModule\Service\NotificationControllerFactory::class,
        ],
    ],

    'router' => [
        'routes' => [
            'cqrs' => [
                'type' => Zend\Mvc\Router\Http\Literal::class,
                'options' => [
                    'route' => '/cqrs',
                ],
                'child_routes' => [
                    'notifications' => [
                        'type' => Zend\Mvc\Router\Http\Segment::class,
                        'options' => [
                            'route' => '/notifications[/:eventId[,:count]]',
                            'defaults' => [
                                'controller' => CQRSModule\Controller\NotificationController::class,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
