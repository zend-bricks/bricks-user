<?php

namespace ZendBricks\BricksUser\Controller;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use ZendBricks\BricksUser\Controller\ProfileOptionController;
use ZendBricks\BricksUser\Api\UserApiInterface;

class ProfileOptionControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $api = $container->get(UserApiInterface::SERVICE_NAME);
        return new ProfileOptionController($api);
    }   
}
