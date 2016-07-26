<?php

namespace ZendBricks\BricksUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZendBricks\BricksUser\Api\UserApiInterface;
use ZendBricks\BricksUser\Form\ProfileOptionsForm;

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
    
    public function manageOptionsAction()
    {
        $dbOptions = $this->api->getProfileOptions();
        
        $form = new ProfileOptionsForm($this->api->getProfileOptions());
        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $formData = $form->getData();
                $this->api->setProfileOptions($formData['options']);
            }
        } else {
            $formData = $this->api->getProfileOptions();
            if ($formData) {
                $form->setData($formData);
            }
        }
        
        return [
            'form' => $form
        ];
    }
}
