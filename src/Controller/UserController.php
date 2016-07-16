<?php

namespace ZendBricks\BricksUser\Controller;

use BricksCommon\Controller\CrudController;
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

    public function __construct(UserApiInterface $api) {
        $this->api = $api;
    }

//    protected function getForm()
//    {
//        return new UserForm();
//    }

//    protected function getListRedirect()
//    {
//        return $this->redirect()->toRoute('user/list');
//    }
//
//    protected function getById($id)
//    {
//        throw new \Exception('Missing implementation for ' . __METHOD__ . ' in ' . self::class);
//    }

    protected function getListPaginator()
    {
        $paginator = new Paginator(new UserPaginatorAdapter($this->api));
        $paginator->setItemCountPerPage(1);
        return $paginator;
    }

//    protected function save($data, $id = null)
//    {
//        throw new Exception('Missing implementation for ' . __METHOD__ . ' in ' . self::class);
//    }
//
//    protected function deleteById($id)
//    {
//        throw new \Exception('Missing implementation for ' . __METHOD__ . ' in ' . self::class);
//    }
}
