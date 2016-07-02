<?php

namespace BricksUser;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'factories' => [
        Controller\ConsoleController::class => Controller\ConsoleControllerFactory::class,
        Controller\AuthController::class => Controller\AuthControllerFactory::class,
        Controller\RoleController::class => InvokableFactory::class,
        Controller\UserController::class => InvokableFactory::class,
    ],
];

