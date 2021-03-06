<?php

namespace ZendBricks\BricksUser\Auth;

use Zend\Authentication\Adapter\AbstractAdapter;
use ZendBricks\BricksUser\Api\UserApiInterface;
use Zend\Crypt\Password\Bcrypt;
use Zend\Authentication\Result;

class ApiAuthAdapter extends AbstractAdapter
{
    protected $userApi;
    
    public function __construct(UserApiInterface $userApi) {
        $this->userApi = $userApi;
    }

    public function authenticate()
    {
        $bcrypt = new Bcrypt();
        if ($bcrypt->verify($this->getCredential(), $this->userApi->getPasswordByUserId($this->getIdentity()))) {
            $code = Result::SUCCESS;
        } else {
            $code = Result::FAILURE;
        }
        return new Result($code, $this->getIdentity());
    }
}
