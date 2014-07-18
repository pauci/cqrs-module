<?php

use CQRS\CommandHandling\SequentialCommandBus;
use CQRS\CommandHandling\TransactionManager\NoTransactionManager;
use CQRS\EventHandling\Publisher\SimpleEventPublisher;
use CQRS\EventHandling\SynchronousEventBus;
use CQRS\Plugin\Doctrine\EventStore\TableEventStore;
use CQRS\Plugin\Doctrine\Type\BinaryUuidType;
use CQRS\Serializer\ReflectionSerializer;
use CQRSModule\CommandHandling\ServiceCommandHandlerLocator;
use CQRSModule\EventHandling\ServiceEventHandlerLocator;
use CQRSModule\Service\CommandBusFactory;
use CQRSModule\Service\CommandHandlerLocatorFactory;
use CQRSModule\Service\EventBusFactory;
use CQRSModule\Service\EventHandlerLocatorFactory;
use CQRSModule\Service\EventPublisherFactory;
use CQRSModule\Service\EventStoreFactory;
use CQRSModule\Service\NotificationControllerFactory;
use CQRSModule\Service\SerializerFactory;
use CQRSModule\Service\TransactionManagerFactory;
use CQRSModule\ServiceFactory\AbstractCqrsServiceFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Rhumsaa\Uuid\Doctrine\UuidType;

return [
    'cqrs' => [
        'command_bus' => [
            'cqrs_default' => [
                'class'                   => SequentialCommandBus::class,
                'command_handler_locator' => 'cqrs_default',
                'transaction_manager'     => 'cqrs_default',
            ]
        ],

        'command_handler_locator' => [
            'cqrs_default' => [
                'class'    => ServiceCommandHandlerLocator::class,
                'handlers' => [
                    /**
                     * CommandInterface handlers in format:
                     *
                     *  '<CommandType>' => '<CommandHandlerServiceName>',
                     *
                     * or:
                     *
                     *  '<CommandHandlerServiceName>' => [
                     *      '<CommandType1>',
                     *      '<CommandType2>',
                     *      ...
                     *  ]
                     */
                ],
            ]
        ],

        'transaction_manager' => [
            'cqrs_default' => [
                'class' => NoTransactionManager::class,
                /**
                 * To use with doctrine:
                 *
                 *  'class'      => 'CQRS\Plugin\Doctrine\CommandHandling\ImplicitOrmTransactionManager',
                 *  'connection' => 'doctrine.entitymanager.orm_default',
                 */
            ]
        ],

        'event_publisher' => [
            'cqrs_default' => [
                'class'     => SimpleEventPublisher::class,
                'event_bus' => 'cqrs_default'
            ]
        ],

        'event_bus' => [
            'cqrs_default' => [
                'class'                 => SynchronousEventBus::class,
                'event_handler_locator' => 'cqrs_default',
                'event_store'           => 'cqrs_default',
            ]
        ],

        'event_handler_locator' => [
            'cqrs_default' => [
                'class'    => ServiceEventHandlerLocator::class,
                'services' => [
                    /**
                     * Example:
                     *
                     *  '<ServiceName>' => [
                     *      '<EventName1>',
                     *      '<EventName2>',
                     *      ...
                     *  ]
                     *
                     * or:
                     *
                     *  [
                     *      'event'    => ['<EventName1>', ...],
                     *      'service'  => ['<ServiceName1', ...],
                     *      'priority' => 1
                     *  ]
                     */
                ],
                'callbacks' => [
                    /**
                     * Example:
                     *
                     *  '<EventName>' => function($event) {},
                     *
                     * or:
                     *
                     *  [
                     *      'event'    => ['<EventName1>, '<EventName2'],
                     *      'callback' => function($event) {},
                     *      'priority' => 1
                     *  ]
                     */
                ],
                'subscribers' => [
                    /**
                     * Array of instances
                     */
                ],
            ]
        ],

        'event_store' => [
            'cqrs_default' => [
                'class'      => TableEventStore::class,
                'connection' => 'doctrine.connection.orm_default',
                'serializer' => 'reflection'
            ]
        ],

        'serializer' => [
            'reflection' => [
                'class' => ReflectionSerializer::class,
            ]
        ],
    ],

    'cqrs_factories' => [
        'command_bus'             => CommandBusFactory::class,
        'command_handler_locator' => CommandHandlerLocatorFactory::class,
        'transaction_manager'     => TransactionManagerFactory::class,
        'event_publisher'         => EventPublisherFactory::class,
        'event_bus'               => EventBusFactory::class,
        'event_handler_locator'   => EventHandlerLocatorFactory::class,
        'event_store'             => EventStoreFactory::class,
        'serializer'              => SerializerFactory::class,
    ],

    'service_manager' => [
        'abstract_factories' => [
            'CQRS' => AbstractCqrsServiceFactory::class,
        ]
    ],

    'doctrine' => [
        'driver' => [
            'CQRS_Driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Domain'
                ]
            ]
        ],
        'configuration' => [
            'orm_default' => [
                'types' => [
                    'uuid'        => UuidType::class,
                    'binary_uuid' => BinaryUuidType::class,
                ]
            ]
        ],
        /*
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    'CQRS\Plugin\Doctrine\Domain\AggregateRootMetadataListener'
                ]
            ]
        ]
        */
    ],

    'controllers' => [
        'factories' => [
            'CQRSModule\Controller\Notification' => NotificationControllerFactory::class,
        ]
    ],

    'router' => [
        'routes' => [
            'cqrs' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/cqrs',
                ],
                'child_routes' => [
                    'notifications' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/notifications',
                            'defaults' => [
                                'controller' => 'CQRSModule\Controller\Notification'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],

    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy'
        ]
    ],
];
