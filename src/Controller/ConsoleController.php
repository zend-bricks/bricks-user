<?php

namespace BricksUser\Controller;

use Zend\Mvc\Console\Controller\AbstractConsoleController;
use Interop\Container\ContainerInterface;
use BricksUser\Model\RoutesExtractor;
use BricksUser\Api\UserApiInterface;

class ConsoleController extends AbstractConsoleController
{
    protected $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function addPermissionsAction()
    {
        /* @var $userApi UserApiInterface */
        $userApi = $this->container->get(UserApiInterface::SERVICE_NAME);
        $existingPermissions = $userApi->getPermissions();
        
        $config = $this->container->get('Config');
        $routesExtractor = new RoutesExtractor($config['router']);
        $routes = $routesExtractor->getRoutes();
        
        foreach ($routes as $route) {
            if (!in_array($route, $existingPermissions, true)) {
                $userApi->addPermission($route);
                echo "added route: $route\n";
            }
        }
        
        $this->container->get('AclCache')->flush();
    }
}
