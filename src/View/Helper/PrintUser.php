<?php

namespace ZendBricks\BricksUser\View\Helper;

use Zend\View\Helper\AbstractHelper;

class PrintUser extends AbstractHelper
{
    public function __invoke($userName, $userId)
    {
        $url = $this->getView()->plugin('url');
        return '<a href="' . $url('profile/show', ['id' => $userId]) . '">' . $userName . '</a>';
    }
}
