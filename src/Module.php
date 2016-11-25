<?php

namespace ZendBricks\BricksUser;

use Zend\Mvc\MvcEvent;
use Zend\Console\Request;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use ZendBricks\BricksUser\Api\UserApiInterface;

class Module
{
    const VERSION = '1.0.0';

    public function getConfig()
    {
        return require __DIR__ . '/../config/module.config.php';
    }
    
    public function onBootstrap(MvcEvent $e)
    {
        if ($e->getRequest() instanceof Request) {  //exclude console-application
            return true;
        }
        
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_ROUTE, [$this, 'initNavigationViewHelper']);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'initNavigationViewHelper']); //fix full navigation
        $eventManager->attach(MvcEvent::EVENT_ROUTE, [$this, 'checkAuth']);
    }
    
    public function initNavigationViewHelper(MvcEvent $e)
    {
        /* @var $container ContainerInterface */
        $container = $e->getApplication()->getServiceManager();
        /* @var $acl \Zend\Permissions\Acl\AclInterface */
        $acl = $container->get('Acl');
        $role = $this->getRole($container);
        
        /* @var $navigationViewhelper \Zend\View\Helper\Navigation */
        $navigationViewhelper = $container->get('ViewHelperManager')->get('navigation');
        $navigationViewhelper->setAcl($acl);
        $navigationViewhelper->setRole($role);
    }

    public function checkAuth(MvcEvent $e)
    {
        /* @var $container ContainerInterface */
        $container = $e->getApplication()->getServiceManager();
        /* @var $navigationViewhelper \Zend\View\Helper\Navigation */
        $navigationViewhelper = $container->get('ViewHelperManager')->get('navigation');
        $acl = $navigationViewhelper->getAcl();
        $role = $navigationViewhelper->getRole();
        
        if (!$acl->isAllowed($role, $e->getRouteMatch()->getMatchedRouteName())) {
            $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, function(MvcEvent $e) {
                $e->stopPropagation();
                
                $response = $e->getResponse();
                $response->setStatusCode(403);
                
                $viewModel = new \Zend\View\Model\ViewModel();
                $viewModel->setTemplate('error/403');
                $e->getViewModel()->addChild($viewModel);
            }, 2);
        }
    }
    
    protected function getRole(ContainerInterface $container)
    {
        /* @var $auth AuthenticationService */
        $auth = $container->get(AuthenticationService::class);
        
        $role = 'Guest';
        if ($auth->getIdentity()) {
            $roleName = $container->get(UserApiInterface::SERVICE_NAME)->getRoleNameByIdentity($auth->getIdentity());
            if ($roleName) {
                $role = $roleName;
            } else {
                $auth->clearIdentity();
            }
        }
        
        return $role;
    }
    
    public function getConsoleUsage($console){
        return [
            'add-permissions' => 'check routing config and add all new routes as new permission',
        ];
    }
}
