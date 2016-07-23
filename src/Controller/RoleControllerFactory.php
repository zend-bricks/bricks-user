<?php

namespace ZendBricks\BricksUser\Controller;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use ZendBricks\BricksUser\Controller\RoleController;
use ZendBricks\BricksUser\Api\UserApiInterface;

class RoleControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $api = $container->get(UserApiInterface::SERVICE_NAME);
        $acl = $container->get('Acl');
        $aclCache = $container->get('AclCache');
        return new RoleController($api, $acl, $aclCache);
    }   
}
