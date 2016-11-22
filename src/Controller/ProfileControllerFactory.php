<?php

namespace ZendBricks\BricksUser\Controller;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use ZendBricks\BricksUser\Controller\ProfileController;
use ZendBricks\BricksUser\Api\UserApiInterface;
use Zend\Authentication\AuthenticationService;

class ProfileControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $api = $container->get(UserApiInterface::SERVICE_NAME);
        $acl = $container->get('Acl');
        $authService = $container->get(AuthenticationService::class);
        $config = $container->get('config');
        return new ProfileController($api, $acl, $authService, $config);
    }   
}
