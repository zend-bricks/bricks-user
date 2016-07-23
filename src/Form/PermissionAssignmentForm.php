<?php

namespace ZendBricks\BricksUser\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Submit;
use Zend\Permissions\Acl\Acl;

class PermissionAssignmentForm extends Form implements InputFilterProviderInterface
{
    protected $permissionGroups = [];

    public function __construct($roleName, array $permissions, Acl $acl) {
        parent::__construct();
        $this->setAttribute('method', 'post');
        
        $roles = $acl->getRoles();
        $parentPermissions = [];
        foreach ($roles as $role) {
            if ($acl->inheritsRole($roleName, $role, true)) {
                foreach ($permissions as $permissionId => $permission) {
                    if ($acl->isAllowed($role, $permission)) {
                        $parentPermissions[$permissionId] = $permissionId;
                    }
                }
            }
        }
        
        $permissionGroups = [];
        foreach ($permissions as $permissionId => $permission) {
            $fragments = explode('/', $permission);
            $groupName = reset($fragments);
            if (!array_key_exists($groupName, $permissionGroups)) {
                $permissionGroups[$groupName] = [];
            }
            $permissionGroups[$groupName][] = $permissionId;
        }
        
        foreach ($permissionGroups as $groupName => $groupPermissions) {
            foreach ($groupPermissions as $permission) {
                $permissionCheck = new Checkbox($permission);
                $permissionCheck->setLabel($permissions[$permission]);
                if (array_key_exists($permission, $parentPermissions)) {
                    $permissionCheck->setValue(true);
                    $permissionCheck->setAttribute('disabled', true);
                }
                $this->add($permissionCheck);
                if (!array_key_exists($groupName, $this->permissionGroups)) {
                    $this->permissionGroups[$groupName] = [];
                }
                $this->permissionGroups[$groupName][] = $permissionCheck;
            }
        }
        
        $submit = new Submit('save');
        $submit->setValue('save');
        $submit->setAttribute('class', 'btn btn-primary');
        $this->add($submit);
    }
    
    public function getPermissionGroups()
    {
        return $this->permissionGroups;
    }
    
    public function getInputFilterSpecification()
    {
        $spec = [];
        foreach ($this->getPermissionGroups() as $group) {
            foreach ($group as $element) {
                $spec[] = [
                    'name' => $element->getName(),
                    'required' => false
                ];
            }
        }
        return $spec;
    }
}
