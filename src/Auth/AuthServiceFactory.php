<?php

namespace BricksUser\Auth;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use BricksUser\Api\UserApiInterface;

class AuthServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var $userApi UserApiInterface */
        $userApi = $container->get(UserApiInterface);
        
        $storage = new ApiSessionStorage($userApi);
        
        $adapter = new ApiAuthAdapter($userApi);
        
        return new AuthenticationService($storage, $adapter);
    }
}
