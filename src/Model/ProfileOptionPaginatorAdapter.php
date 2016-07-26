<?php

namespace ZendBricks\BricksUser\Model;

use Zend\Paginator\Adapter\AdapterInterface;
use ZendBricks\BricksUser\Api\UserApiInterface;

class ProfileOptionPaginatorAdapter implements AdapterInterface
{
    protected $api;

    public function __construct(UserApiInterface $api) {
        $this->api = $api;
    }

    public function getItems($offset, $itemCountPerPage)
    {
        return $this->api->getProfileOptions($offset, $itemCountPerPage);
    }
    
    public function count()
    {
        return $this->api->countProfileOptions();
    }
}
