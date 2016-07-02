<?php

namespace BricksUser;

return [
    'router' => [
        'routes' => [
            'add-permissions' => [
                'options' => [
                    'route'    => 'add-permissions',
                    'defaults' => [
                        'controller' => Controller\ConsoleController::class,
                        'action' => 'addPermissions'
                    ]
                ]
            ],
        ]
    ]
];