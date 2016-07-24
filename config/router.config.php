<?php

namespace ZendBricks\BricksUser;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use ZendBricks\BricksUser\Controller\AuthController;
use ZendBricks\BricksUser\Controller\RoleController;
use ZendBricks\BricksUser\Controller\UserController;
use ZendBricks\BricksUser\Controller\ProfileController;

return [
    'router_class' => \Zend\Mvc\I18n\Router\TranslatorAwareTreeRouteStack::class,
    'routes' => [
        'auth' => [
            'type' => Literal::class,
            'options' => [
                'route' => '/auth',
                'defaults' => [
                    'controller' => AuthController::class
                ]
            ],
            'child_routes' => [
                'login' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/login',
                        'defaults' => [
                            'action' => 'login',
                        ],
                    ],
                ],
                'logout' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/logout',
                        'defaults' => [
                            'action' => 'logout',
                        ],
                    ],
                ],
                'register' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/register',
                        'defaults' => [
                            'action' => 'register',
                        ],
                    ],
                ],
                'confirmRegistration' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/confirm-registration/:token',
                        'defaults' => [
                            'action' => 'confirmRegistration',
                        ],
                    ],
                ],
                'resendRegisterMail' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/resend-register-mail',
                        'defaults' => [
                            'action' => 'resendRegisterMail',
                        ],
                    ],
                ],
                'forgotPassword' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/forgot-password',
                        'defaults' => [
                            'action' => 'forgotPassword',
                        ],
                    ],
                ],
                'changePassword' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/change-password[/:token]',
                        'defaults' => [
                            'action' => 'changePassword',
                        ],
                    ],
                ],
                'selfDelete' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/self-delete',
                        'defaults' => [
                            'action' => 'selfDelete',
                        ],
                    ],
                ],
                'confirmSelfDelete' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/confirm-self-delete/:token',
                        'defaults' => [
                            'action' => 'confirmSelfDelete',
                        ],
                    ],
                ]
            ]
        ],
        'role' => [
            'type' => Literal::class,
            'options' => [
                'route' => '/role',
                'defaults' => [
                    'controller' => RoleController::class
                ]
            ],
            'child_routes' => [
                'list' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/list[/:page]',
                        'defaults' => [
                            'action' => 'list',
                        ],
                    ],
                ],
                'create' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/create',
                        'defaults' => [
                            'action' => 'create',
                        ],
                    ],
                ],
                'show' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/show/:id',
                        'defaults' => [
                            'action' => 'show',
                        ],
                    ],
                ],
                'edit' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/edit/:id',
                        'defaults' => [
                            'action' => 'edit',
                        ],
                    ],
                ],
                'delete' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/delete/:id',
                        'defaults' => [
                            'action' => 'delete',
                        ],
                    ],
                ],
                'permissionAssignment' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/permissions-assignment/:roleId',
                        'defaults' => [
                            'action' => 'permissionAssignment',
                        ],
                    ],
                ],
            ]
        ],
        'user' => [
            'type' => Literal::class,
            'options' => [
                'route' => '/user',
                'defaults' => [
                    'controller' => UserController::class
                ]
            ],
            'child_routes' => [
                'list' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/list[/:page]',
                        'defaults' => [
                            'action' => 'list',
                        ],
                    ],
                ],
                'show' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/show/:id',
                        'defaults' => [
                            'action' => 'show',
                        ],
                    ],
                ],
                'edit' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/edit/:id',
                        'defaults' => [
                            'action' => 'edit',
                        ],
                    ],
                ],
                'delete' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/delete/:id',
                        'defaults' => [
                            'action' => 'delete',
                        ],
                    ],
                ]
            ]
        ],
        'profile' => [
            'type' => Literal::class,
            'options' => [
                'route' => '/profile',
                'defaults' => [
                    'controller' => ProfileController::class
                ]
            ],
            'child_routes' => [
                'show' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/show[/:id]',
                        'defaults' => [
                            'action' => 'show',
                        ],
                    ],
                ],
                'edit' => [
                    'type' => Segment::class,
                    'options' => [
                        'route' => '/edit/:id',
                        'defaults' => [
                            'action' => 'edit',
                        ],
                    ],
                ],
                'settings' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/settings',
                        'defaults' => [
                            'action' => 'settings',
                        ],
                    ],
                ],
                'manageOptions' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/manage-options',
                        'defaults' => [
                            'action' => 'manageOptions',
                        ],
                    ],
                ]
            ]
        ]
    ]
];

