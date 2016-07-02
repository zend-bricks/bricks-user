<?php

namespace ZendBricks\BricksUser;

use Zend\Authentication\AuthenticationService;

return [
    'factories' => [
        AuthenticationService::class => Auth\AuthServiceFactory::class,
        'Acl' => Auth\AclFactory::class,
        'UserMailModel' => Model\UserMailModelFactory::class
    ]
];
