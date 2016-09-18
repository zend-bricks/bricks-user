<?php

namespace ZendBricks\BricksUser\Form;

use Zend\Form\Form;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Submit;

class SelfDeleteForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'panel-body');        
        
        $confirm = new Checkbox('confirm');
        $confirm->setLabel('confirm.account.deletion');
        $confirm->setAttribute('class', 'checkbox form-control');
        $this->add($confirm);
        
        $submit = new Submit('send');
        $submit->setValue('delete.account');
        $submit->setAttribute('class', 'btn btn-danger');
        $this->add($submit);
    }
}
