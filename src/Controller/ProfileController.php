<?php

namespace ZendBricks\BricksUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZendBricks\BricksUser\Api\UserApiInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Authentication\AuthenticationService;
use ZendBricks\BricksUser\Form\ProfileForm;

class ProfileController extends AbstractActionController
{
    protected $api;
    protected $acl;
    protected $authService;

    public function __construct(UserApiInterface $api, Acl $acl, AuthenticationService $authService)
    {
        $this->api = $api;
        $this->acl = $acl;
        $this->authService = $authService;
    }
    
    public function showAction()
    {
        $id = $this->params()->fromRoute('id', $this->authService->getIdentity());
        $profileData = $this->api->getProfileSettings($id);

        return [
            'identity' => $this->authService->getIdentity(),
            'userId' => $id,
            'displayName' => $this->api->getUsernameByUserId($id),
            'profileData' => $profileData
        ];
    }
    
    public function editAction()
    {
        $id = $this->params()->fromRoute('id', $this->authService->getIdentity());
        $form = $this->getProfileForm();
        
        foreach ($this->api->getProfileSettings($id) as $profileSetting) {
            $form->get($profileSetting['name'])->setValue($profileSetting['value']);
        }
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $formData = $form->getData();
                unset($formData['save']);
                $this->api->setProfileSettings($id, $formData);
                return $this->getProfileRedirect($id);
            }
        }
        
        return [
            'form' => $form
        ];
    }
    
    protected function getProfileForm()
    {
        return new ProfileForm($this->api->getProfileOptions(0, 100));
    }
    
    protected function getProfileRedirect($id)
    {
        return $this->redirect()->toRoute('profile/show', ['id' => $id]);
    }
}
