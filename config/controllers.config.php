<?php

namespace ZendBricks\BricksUser;

return [
    'factories' => [
        Controller\ConsoleController::class => Controller\ConsoleControllerFactory::class,
        Controller\AuthController::class => Controller\AuthControllerFactory::class,
        Controller\RoleController::class => Controller\RoleControllerFactory::class,
        Controller\UserController::class => Controller\UserControllerFactory::class,
        Controller\ProfileController::class => Controller\ProfileControllerFactory::class,
    ],
];

