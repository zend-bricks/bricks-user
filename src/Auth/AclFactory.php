<?php

namespace ZendBricks\BricksUser\Auth;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Permissions\Acl\Acl;
use ZendBricks\BricksUser\Api\UserApiInterface;

class AclFactory implements FactoryInterface
{
    protected $addedRoles = [];

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var $aclCache \Zend\Cache\Storage\StorageInterface */
        $aclCache = $container->get('AclCache');
        $acl = $aclCache->getItem('Acl');
        if (!$acl) {
            /* @var $userApi UserApiInterface */
            $userApi = $container->get(UserApiInterface::SERVICE_NAME);

            $acl = new Acl();

            $resources = $userApi->getPermissions();
            foreach ($resources as $resource) {
                $acl->addResource($resource);
            }

            $roles = $userApi->getRolesAndParent();
            foreach ($roles as $role => $parents) {
                $this->addRoleToAcl($role, $roles, $acl);
            }

            $rolePermissions = $userApi->getRolePermissions();
            foreach ($rolePermissions as $role => $permissions) {
                $acl->allow($role, $permissions);
            }
            
            $deniedRolePermissions = $userApi->getDeniedRolePermissions();
            foreach ($deniedRolePermissions as $role => $permissions) {
                $acl->deny($role, $permissions);
            }
            
            $aclCache->setItem('Acl', $acl);
        }
        
        return $acl;
    }
    
    protected function addRoleToAcl($role, array $roles, Acl $acl)
    {
        if (!$acl->hasRole($role)) {
            if (array_key_exists($role, $this->addedRoles)) {   //cyclic parents fault handling
                $acl->addRole($role);   //add role without its parents to escape cycle
            } else {
                $this->addedRoles[$role] = true;
                foreach ($roles[$role] as $parent) {    //add parent roles first
                    $this->addRoleToAcl($parent, $roles, $acl);
                }
                if (!$acl->hasRole($role)) {    //extra check because role could be added in previous cycle
                    $acl->addRole($role, $roles[$role]);
                }
            }
        }
    }
}
