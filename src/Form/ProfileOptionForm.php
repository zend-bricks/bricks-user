<?php

namespace ZendBricks\BricksUser\Form;

use Zend\Form\Form;
use Zend\Form\Element\Text;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;

class ProfileOptionForm extends Form
{
    const INPUT_TYPE_TEXT = 'text';
    const INPUT_TYPE_TEXTAREA = 'textarea';
    const INPUT_TYPE_DATE = 'date';
    const INPUT_TYPE_TIME = 'time';
    const INPUT_TYPE_DATETIME = 'datetime';
    
    public function __construct() {
        parent::__construct();
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'panel-body');
        
        $name = new Text('name');
        $name->setLabel('name');
        $name->setAttribute('class', 'form-control');
        $this->add($name);
        
        $type = new Select('inputType');
        $type->setAttribute('class', 'form-control');
        $type->setLabel('input.type');
        $type->setValueOptions([
            self::INPUT_TYPE_TEXT,
            self::INPUT_TYPE_TEXTAREA,
            self::INPUT_TYPE_DATE,
            self::INPUT_TYPE_TIME,
            self::INPUT_TYPE_DATETIME
        ]);
        $this->add($type);
        
        $submit = new Submit('save');
        $submit->setValue('save');
        $submit->setAttribute('class', 'btn btn-primary');
        $this->add($submit);
    }
}
