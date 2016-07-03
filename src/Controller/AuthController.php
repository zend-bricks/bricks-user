<?php

namespace ZendBricks\BricksUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZendBricks\BricksUser\Api\UserApiInterface;
use Zend\Authentication\AuthenticationService;
use ZendBricks\BricksUser\Form\LoginForm;
use ZendBricks\BricksUser\Form\RegisterForm;
use Zend\Crypt\Password\Bcrypt;
use ZendBricks\BricksUser\Model\UserMailModel;
use Zend\Authentication\Result;
use ZendBricks\BricksUser\Form\SpecifyMailForm;
use ZendBricks\BricksUser\Form\ChangePasswordForm;

/**
 * User Authentication
 */
class AuthController extends AbstractActionController
{
    protected $api;
    protected $authService;
    protected $mailModel;
    protected $projectName;

    public function __construct(UserApiInterface $api, AuthenticationService $authService, UserMailModel $mailModel, $projectName) {
        $this->api = $api;
        $this->authService = $authService;
        $this->mailModel = $mailModel;
        $this->projectName = $projectName;
    }

    public function loginAction()
    {
        $form = new LoginForm();
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $formData = $form->getData();
                /* @var $adapter \Zend\Authentication\Adapter\AbstractAdapter */
                $adapter = $this->authService->getAdapter();
                $userId = $this->api->getIdByUsername($formData['username']);
                $adapter->setIdentity($userId);
                $adapter->setCredential($formData['password']);
                if ($this->authService->authenticate()->getCode() == Result::SUCCESS) {
                    if ($this->api->isUserActivated($userId)) {
                        return $this->redirect()->toRoute('home');
                    } else {
                        $this->authService->clearIdentity();
                        $this->flashMessenger()->addErrorMessage('user.not.active');
                    }
                } else {
                    $this->flashMessenger()->addErrorMessage('login.failed');
                }
            }        
        }
        return [
            'form' => $form
        ];
    }
    
    public function logoutAction()
    {
        $this->authService->clearIdentity();
        return $this->redirect()->toRoute('home');
    }
    
    public function registerAction()
    {
        $form = new RegisterForm();        
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $formData = $form->getData();
                if ($this->api->getIdByUsername($formData['username'])) {
-                   $this->flashMessenger()->addErrorMessage('username.in.use');
                } elseif ($this->api->getIdByEmail($formData['email'])) {
                    $this->flashMessenger()->addErrorMessage('email.in.use');
                } else {
                    $passwordHash = $this->createPassword($formData['password']);
                    $userId = $this->api->registerUser($formData['username'], $formData['email'], $passwordHash);
                    $token = $this->generateToken();
                    $this->api->createRegisterToken($userId, $token);
                    $this->mailModel->sendConfirmRegistrationMail($formData['email'], $formData['username'], $token, $this->projectName);
                    return $this->redirect()->toRoute('home');
                }
            }        
        }        
        return [
            'form' => $form
        ];
    }
    
    public function confirmRegistrationAction()
    {
        $token = $this->params()->fromRoute('token');
        $userId = $this->api->getUserIdByRegisterToken($token);
        if ($userId) {
            $this->api->activateUser($userId);
            $this->api->deleteRegisterToken($userId);
            $this->api->onRoleChanged($userId);
            $this->flashMessenger()->addSuccessMessage('user.activated');
        } else {
            $this->flashMessenger()->addErrorMessage('user.not.activated');
        }
        return $this->redirect()->toRoute('home');
    }
    
    public function resendRegisterMailAction()
    {
        $form = new SpecifyMailForm();
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $formData = $form->getData();
                $userId = $this->api->getIdByEmail($formData['email']);
                $username = $this->api->getUsernameById($userId);
                $token = $this->generateToken();
                $this->api->createRegisterToken($userId, $token);
                $this->mailModel->sendConfirmRegistrationMail($formData['email'], $username, $token, $this->projectName);
                $this->flashMessenger()->addErrorMessage('resent.mail.if.exists');
                return $this->redirect()->toRoute('auth/login');
            }        
        }
        return [
            'form' => $form
        ];
    }
    
    public function forgotPasswordAction()
    {
        $form = new SpecifyMailForm();
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $formData = $form->getData();
                $userId = $this->api->getIdByEmail($formData['email']);
                if ($userId) {
                    $username = $this->api->getUsernameById($userId);
                    $token = $this->generateToken();
                    $this->api->createPasswordToken($userId, $token);
                    $this->mailModel->sendForgotPasswordMail($formData['email'], $username, $token, $this->projectName);
                    $this->flashMessenger()->addSuccessMessage('sent.email.change.password');
                    return $this->redirect()->toRoute('home');
                } else {
                    $this->flashMessenger()->addErrorMessage('email.not.in.use');
                    return $this->redirect()->toRoute('auth/forgotPassword');
                }
            }
        }
        return [
            'form' => $form
        ];
    }
    
    public function changePasswordAction()
    {
        $token = $this->params()->fromRoute('token', false);
        $userId = false;
        if ($token) {
            $userId = $this->api->getUserIdByPasswordToken($token);
            if (!$userId) {
                $this->flashMessenger()->addErrorMessage('invalid.token');
                return $this->redirect()->toRoute('home');
            }
        } elseif (!$this->authService->getIdentity()) {
            $this->flashMessenger()->addErrorMessage('can.not.change.password.as.guest');
            return $this->redirect()->toRoute('home');
        }
        
        $form = new ChangePasswordForm(!$userId);
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $formData = $form->getData();
                if ($userId) {
                    $this->api->setPassword($userId, $this->createPassword($formData['password']));
                    $this->flashMessenger()->addSuccessMessage('password.changed');
                    return $this->redirect()->toRoute('auth/login');
                } else {
                    $adapter = $this->authService->getAdapter();
                    $adapter->setIdentity($this->authService->getIdentity());
                    $adapter->setCredential($formData['oldPassword']);
                    if ($adapter->authenticate()->getCode() == Result::SUCCESS) {
                        $this->api->setPassword($this->authService->getIdentity(), $this->createPassword($formData['password']));
                        $this->flashMessenger()->addSuccessMessage('password.changed');
                        return $this->redirect()->toRoute('auth/logout');
                    } else {
                        $this->flashMessenger()->addErrorMessage('invalid.password');
                    }
                }
            }
        }
        return [
            'form' => $form
        ];
    }
    
    public function selfDeleteAction()
    {
        
    }
    
    public function confirmSelfDeleteAction()
    {
        
    }
    
    /**
     * @return string(52) random
     */
    protected function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(26));
    }
    
    /**
     * crypt password
     * 
     * @param string $password
     * @return string(60)
     */
    protected function createPassword($password)
    {
        $bcrypt = new Bcrypt();
        return $bcrypt->create($password);
    }
}
