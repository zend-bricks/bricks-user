<?php

namespace ZendBricks\BricksUser\Form;

use Zend\Form\Form;
use Zend\Form\Element\Text;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Email;

class UserForm extends Form
{
    public function __construct() {
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'panel-body');
        
        $username = new Text('username');
        $username->setLabel('username');
        $username->setAttribute('class', 'form-control');
        $username->setAttribute('data-urr', '/isusernameinuse');
        $this->add($username);
        
        $mail = new Email('email');
        $mail->setLabel('email');
        $mail->setAttribute('class', 'form-control');
        $this->add($mail);
        
        $submit = new Submit('save');
        $submit->setValue('save');
        $submit->setAttribute('class', 'btn btn-default');
        $this->add($submit);
    }
}
