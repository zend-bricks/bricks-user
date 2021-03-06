<?php

namespace ZendBricks\BricksUser\Api;

interface UserApiInterface {
    const SERVICE_NAME = 'BricksUserApi';
    
    /**
     * Write $identity into session storage using the key $sessionId
     * 
     * @param string $sessionId should be a secure random generated string
     * @param string $identity in most cases the User-ID
     */
    public function setSessionIdentity($sessionId, $identity);
    
    /**
     * Read the identity from session storage using the key $sessionId
     * 
     * @param string $sessionId should be a secure random generated string
     */
    public function getSessionIdentity($sessionId);
    
    /**
     * Clear the session with $sessionId
     * 
     * @param string $sessionId should be a secure random generated string
     */
    public function clearSessionIdentity($sessionId);

    /**
     * Get the id of a specific user
     * 
     * @param string $username
     * @return int
     */
    public function getUserIdByUsername($username);
    
    /**
     * @param int|string $userId
     * @return string
     */
    public function getUsernameByUserId($userId);
    
    /**
     * @param string $email
     * @return int
     */
    public function getUserIdByEmail($email);
    
    /**
     * @param int|string $userId
     * @return string
     */
    public function getEmailByUserId($userId);

    /**
     * Get the password-hash of a specific user
     * this will be used for login
     * 
     * @param int|string $userId
     * @return string bcrypt hash of the password
     */
    public function getPasswordByUserId($userId);

    /**
     * @param int|string $userId
     * @return bool
     */
    public function isUserActivated($userId);
    
    /**
     * @param int|string $userId
     */
    public function activateUser($userId);
    
    /**
     * @return int count of users
     */
    public function countUsers();
    
    /**
     * Get $itemCountPerPage users with $offset
     * 
     * @param int $offset
     * @param int $itemCountPerPage
     */
    public function getUsers($offset, $itemCountPerPage);
    
    /**
     * @param array $data
     * @param int|user $userId
     */
    public function saveUser($data, $userId = null);
    
    /**
     * @param int|string $userId
     */
    public function getUserData($userId);

    /**
     * @param int|string $userId
     */
    public function deleteUser($userId);
    
    /**
     * Get the role name of the user
     * 
     * @param int|string $userId
     */
    public function getRoleNameByIdentity($userId);
    
    /**
     * Add a new permission
     * 
     * @param string $name
     */
    public function addPermission($name);
    
    /**
     * returns permissions this way:
     * [
     *     'default/home',
     *     'application/index',
     *     'album/delete'
     * ]
     * 
     * @return array all available permissions
     */
    public function getPermissions();
    
    /**
     * Save data to role with $id. If no $id is set a new entry will be created
     * 
     * @param array $data
     * @param int|string $id
     * @return bool|int|string success state or insert id
     */
    public function saveRole($data, $id = null);
    
    /**
     * @param int|string $roleId
     * @return string
     */
    public function getRoleName($roleId);
    
    /**
     * Get saved data of the role
     * 
     * @param int|string $id
     * @return array
     */
    public function getRoleData($id);
    
    /**
     * Delete the role
     * 
     * @param int|string $id
     * @return bool success
     */
    public function deleteRole($id);
    
    /**
     * return int count of roles
     */
    public function countRoles();
    
    /**
     * Get $itemCountPerPage roles with $offset
     * 
     * @param int $offset
     * @param int $itemCountPerPage
     */
    public function getRoles($offset, $itemCountPerPage);
    
    /**
     * @return array role names by id
     */
    public function getRoleNames();
    
    /**
     * returns roles with parent roles this way:
     * [
     *     'Guest' => [],
     *     'User' => ['Guest'],
     *     'Moderator' => ['User']
     * ]
     * 
     * @return array all available roles
     */
    public function getRolesAndParent();
    
    /**
     * @param int|string $roleId
     * @return array contains all directly assigned permission ids for the role
     */
    public function getPermissionsOfRole($roleId);
    
    /**
     * @param int|string $roleId
     * @param array $permissions
     * @return bool success
     */
    public function setRolePermissions($roleId, $permissions);

    /**
     * returns granted role - permission combinations this way:
     * [
     *     'Guest' => [
     *         'default/home',
     *         'application/index'
     *     ],
     *     'Moderator' => [
     *         'album/delete'
     *     ]
     * ]
     * 
     * @return array all granted permissions
     */
    public function getRolePermissions();
    
    /**
     * returns granted role - permission combinations this way:
     * [
     *     'User' => [
     *         'auth/login',
     *         'auth/register'
     *     ]
     * ]
     * 
     * @return array all denied permissions
     */
    public function getDeniedRolePermissions();
    
    /**
     * Add an unactivated user
     * 
     * @param string $username
     * @param string $email
     * @param string $password
     * @return int|string $userId
     */
    public function registerUser($username, $email, $password);
    
    /**
     * Delete all old register tokens of the user and save the new given $token
     * 
     * @param int|string $userId
     * @param string $token
     */
    public function createRegisterToken($userId, $token);
    
    /**
     * @param string $token
     * @return int|string
     */
    public function getUserIdByRegisterToken($token);
            
    /**
     * @param int|string $userId
     */
    public function deleteRegisterToken($userId);
    
    /**
     * @param int|string $userId
     * @param string $token
     */
    public function createPasswordToken($userId, $token);

    /**
     * @param string $token
     * @return int|string
     */
    public function getUserIdByPasswordToken($token);
    
    /**
     * @param int|string $userId
     */
    public function deletePasswordToken($userId);
    
    /**
     * @param int|string $userId
     * @param string $password
     */
    public function setPassword($userId, $password);
    
    /**
     * @param int|string $userId
     * @param string $token
     */
    public function createDeleteToken($userId, $token);
    
    /**
     * @param string $token
     * @return int|string
     */
    public function getUserIdByDeleteToken($token);
    
    /**
     * @param int|string $userId
     */
    public function deleteDeleteToken($userId);
    
    /**
     * @return array options for profile settings
     */
    public function getProfileOptions($offset, $itemCountPerPage);
    
    public function saveProfileOption($data, $id = null);
    
    public function getProfileOptionData($id);
    
    public function deleteProfileOption($id);
    
    public function countProfileOptions();
    
    public function getProfileSettings($userId);
    
    public function setProfileSettings($userId, array $data);
    
    public function mayIdentityEditAnyProfile($userId);
    
    /**
     * Event that is triggered when a new role is assigned to a user
     * This event could be used for cache-control, info mails ...
     * 
     * @param int|string $userId
     */
    public function onUserRoleChanged($userId);
}
