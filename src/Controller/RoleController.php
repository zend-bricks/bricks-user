<?php

namespace ZendBricks\BricksUser\Controller;

use BricksCommon\Controller\CrudController;
use ZendBricks\BricksUser\Api\UserApiInterface;
use Zend\Paginator\Paginator;
use ZendBricks\BricksUser\Model\RolePaginatorAdapter;

/**
 * Admin create, read, update, delete Role actions
 */
class RoleController extends CrudController
{
    protected $api;

    public function __construct(UserApiInterface $api) {
        $this->api = $api;
    }
    
    public function permissionAssignmentAction()
    {
        
    }
    
    protected function getListPaginator()
    {
        $paginator = new Paginator(new RolePaginatorAdapter($this->api));
        return $paginator;
    }
}
