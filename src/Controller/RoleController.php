<?php

namespace ZendBricks\BricksUser\Controller;

use BricksCommon\Controller\CrudController;
use ZendBricks\BricksUser\Api\UserApiInterface;
use Zend\Paginator\Paginator;
use ZendBricks\BricksUser\Model\RolePaginatorAdapter;
use ZendBricks\BricksUser\Form\RoleForm;

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
        $paginator->setItemCountPerPage(20);
        return $paginator;
    }
    
    protected function getForm()
    {
        return new RoleForm($this->api->getRoleNames());
    }
    
    protected function save($data, $id = null)
    {
        return $this->api->saveRole($data, $id);
    }
    
    protected function getData($id)
    {
        return $this->api->getRoleData($id);
    }
    
    protected function deleteById($id)
    {
        return $this->api->deleteRole($id);
    }
    
    protected function getListRedirect()
    {
        return $this->redirect()->toRoute('role/list');
    }
}
