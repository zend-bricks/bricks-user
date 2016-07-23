<?php

namespace ZendBricks\BricksUser\Form;

use Zend\Form\Form;
use Zend\Form\Element\Text;
use Zend\Form\Element\MultiCheckbox;
use Zend\Form\Element\Submit;

class RoleForm extends Form
{
    public function __construct(array $roles) {
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'panel-body');
        
        $name = new Text('name');
        $name->setLabel('name');
        $name->setAttribute('class', 'form-control');
        $this->add($name);
        
        $parent = new MultiCheckbox('parent');
        $parent->setLabel('parent');
        $parent->setUseHiddenElement(true);
        $parent->setValueOptions($roles);
        $this->add($parent);
        $this->getInputFilter()->get($parent->getName())->setAllowEmpty(true);
        
        $submit = new Submit('save');
        $submit->setValue('save');
        $submit->setAttribute('class', 'btn btn-primary');
        $this->add($submit);
    }
}
