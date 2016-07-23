<?php

namespace ZendBricks\BricksUser\Controller;

use ZendBricks\BricksCommon\Controller\CrudController;
use ZendBricks\BricksUser\Api\UserApiInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Cache\Storage\StorageInterface;
use Zend\Paginator\Paginator;
use ZendBricks\BricksUser\Model\RolePaginatorAdapter;
use ZendBricks\BricksUser\Form\RoleForm;
use ZendBricks\BricksUser\Form\PermissionAssignmentForm;

/**
 * Admin create, read, update, delete Role actions
 */
class RoleController extends CrudController
{
    protected $api;
    protected $acl;
    protected $aclCache;

    public function __construct(UserApiInterface $api, Acl $acl, StorageInterface $aclCache) {
        $this->api = $api;
        $this->acl = $acl;
        $this->aclCache = $aclCache;
    }
    
    public function permissionAssignmentAction()
    {
        $roleId = $this->params()->fromRoute('roleId');
        $roleName = $this->api->getRoleName($roleId);
        $permissions = $this->api->getPermissions();
        $form = new PermissionAssignmentForm($roleName, $permissions, $this->acl);
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $formData = $form->getData();
                $nextPermissions = [];
                foreach ($formData as $permission => $checked) {
                    if ($checked) {
                        $nextPermissions[] = $permission;
                    }
                }
                if ($this->api->setRolePermissions($roleId, $nextPermissions)) {
                    $this->aclCache->flush();
                    $this->onEditSuccess($formData);
                    $this->flashMessenger()->addSuccessMessage('edit.success');
                    return $this->getListRedirect();
                } else {
                    $this->flashMessenger()->addErrorMessage('edit.failed');
                }
            }
        } else {
            $rolePermissions = $this->api->getPermissionsOfRole($roleId);
            $formData = [];
            foreach ($rolePermissions as $permission) {
                $formData[$permission] = 1;
            }
            $form->setData($formData);
        }
        
        return [
            'form' => $form
        ];
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
    
    protected function onCreateSuccess(array $formData) {
        $this->aclCache->flush();
    }
    
    protected function onEditSuccess(array $formData) {
        $this->onCreateSuccess($formData);
    }
}
