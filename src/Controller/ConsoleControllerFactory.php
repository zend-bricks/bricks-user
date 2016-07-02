<?php

namespace ZendBricks\BricksUser\Controller;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use ZendBricks\BricksUser\Controller\ConsoleController;

class ConsoleControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ConsoleController($container);
    }
}
