<?php

namespace ZendBricks\BricksUser\Controller;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use ZendBricks\BricksUser\Controller\AuthController;
use Zend\Authentication\AuthenticationService;
use ZendBricks\BricksUser\Api\UserApiInterface;

class AuthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $api = $container->get(UserApiInterface::SERVICE_NAME);
        $authService = $container->get(AuthenticationService::class);
        $mailModel = $container->get('UserMailModel');
        $config = $container->get('config');
        $projectName = $config['project.name'];
        $userRoleCache = $container->get('UserRoleCache');
        return new AuthController($api, $authService, $mailModel, $projectName, $userRoleCache);
    }   
}
