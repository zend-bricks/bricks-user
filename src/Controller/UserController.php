<?php

namespace ZendBricks\BricksUser\Controller;

use ZendBricks\BricksCommon\Controller\CrudController;
use ZendBricks\BricksUser\Api\UserApiInterface;
use ZendBricks\BricksUser\Form\UserForm;
use Zend\Paginator\Paginator;
use ZendBricks\BricksUser\Model\UserPaginatorAdapter;

/**
 * Admin create, read, update, delete User actions
 */
class UserController extends CrudController
{
    protected $api;

    public function __construct(UserApiInterface $api)
    {
        $this->api = $api;
    }
    
    protected function getListPaginator()
    {
        $paginator = new Paginator(new UserPaginatorAdapter($this->api));
        $paginator->setItemCountPerPage(self::ITEMS_PER_PAGE);
        return $paginator;
    }
    
    protected function getForm()
    {
        return new UserForm($this->api->getRoleNames());
    }
    
    protected function save($data, $id = null)
    {
        return $this->api->saveUser($data, $id);
    }
    
    protected function getData($id)
    {
        return $this->api->getUserData($id);
    }
    
    protected function deleteById($id)
    {
        return $this->api->deleteUser($id);
    }
    
    protected function getListRedirect()
    {
        return $this->redirect()->toRoute('user/list');
    }
}
