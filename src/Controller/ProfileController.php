<?php

namespace ZendBricks\BricksUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZendBricks\BricksUser\Api\UserApiInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Authentication\AuthenticationService;
use ZendBricks\BricksUser\Form\ProfileForm;
use Zend\Form\Form;
use Zend\Form\Element\File;

class ProfileController extends AbstractActionController
{
    protected $api;
    protected $acl;
    protected $authService;
    protected $config;

    public function __construct(UserApiInterface $api, Acl $acl, AuthenticationService $authService, array $config)
    {
        $this->api = $api;
        $this->acl = $acl;
        $this->authService = $authService;
        $this->config = $config;
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
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData(array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            ));
            if ($form->isValid()) {
                $this->api->setProfileSettings($id, $this->getPreparedFormData($form));
                return $this->getProfileRedirect($id);
            }
        }
        
        return [
            'form' => $form
        ];
    }
    
    protected function getProfileForm()
    {
        return new ProfileForm($this->api->getProfileOptions(0, 100), $this->config['profile']);
    }
    
    protected function getProfileRedirect($id)
    {
        return $this->redirect()->toRoute('profile/show', ['id' => $id]);
    }
    
    protected function getPreparedFormData(Form $form)
    {
        $formData = $form->getData();
        foreach ($form->getElements() as $element) {
            if ($element instanceof File) {
                if ($formData[$element->getName()]['size'] == 0) {
                    unset($formData[$element->getName()]);
                } else {
                    $formData[$element->getName()] = $formData[$element->getName()]['tmp_name'];
                }
            }
        }
        unset($formData['save']);
        return $formData;
    }
}
