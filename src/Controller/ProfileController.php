<?php

namespace ZendBricks\BricksUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZendBricks\BricksUser\Api\UserApiInterface;

class ProfileController extends AbstractActionController
{
    protected $api;

    public function __construct(UserApiInterface $api)
    {
        $this->api = $api;
    }
    
    public function showAction()
    {
        
    }
    
    public function editAction()
    {
        
    }
    
    public function settingsAction()
    {
        
    }
    
    public function manageOptionsAction()
    {
        
    }
}
