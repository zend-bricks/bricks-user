<?php

namespace ZendBricks\BricksUser\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Permissions\Acl\Exception\InvalidArgumentException;

class IsAllowed extends AbstractHelper
{
    protected $navigation;
    protected $acl;
    protected $roleName;

    public function __invoke($route)
    {
        try {
            return $this->getAcl()->isAllowed($this->getRoleName(), $route);
        } catch (InvalidArgumentException $ex) {
            return false;
        }
    }

    /**
     * @return \Zend\View\Helper\Navigation
     */
    protected function getNavigation()
    {
        if (!$this->navigation) {
            $this->navigation = $this->getView()->getHelperPluginManager()->get('navigation');
        }
        return $this->navigation;
    }
    
    /**
     * @return \Zend\Permissions\Acl\Acl
     */
    protected function getAcl()
    {
        if (!$this->acl) {
            $this->acl = $this->getNavigation()->getAcl();
        }
        return $this->acl;
    }
    
    protected function getRoleName()
    {
        if (!$this->roleName) {
            $this->roleName = $this->getNavigation()->getRole();
        }
        return $this->roleName;
    }
}
