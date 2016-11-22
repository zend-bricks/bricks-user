<?php

namespace ZendBricks\BricksUser\Form;

use Zend\Form\Form;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\File;
use Zend\Form\Element\Submit;
use ZendBricks\BricksUser\Form\ProfileOptionForm;
use Zend\InputFilter\FileInput;

class ProfileForm extends Form
{
    public function __construct(array $profileOptions, array $config) {
        parent::__construct();
        $this->setAttribute('method', 'post');
        
        foreach ($profileOptions as $profileOption) {
            switch ($profileOption['inputType']) {
                case ProfileOptionForm::INPUT_TYPE_TEXTAREA:
                    $element = new Textarea($profileOption['name']);
                    break;
                case ProfileOptionForm::INPUT_TYPE_TEXT:
                    $element = new Text($profileOption['name']);
                    break;
                case ProfileOptionForm::INPUT_TYPE_DATE:
                    $element = new Text($profileOption['name']);
                    break;
                case ProfileOptionForm::INPUT_TYPE_TIME:
                    $element = new Text($profileOption['name']);
                    break;
                case ProfileOptionForm::INPUT_TYPE_DATETIME:
                    $element = new Text($profileOption['name']);
                    break;
                case ProfileOptionForm::INPUT_TYPE_PICTURE_UPLOAD:
                    $element = new File($profileOption['name']);
                    $element->prepareElement($this);
                    if (array_key_exists($profileOption['name'], $config['upload_target'])) {
                        $uploadTarget = $config['upload_target'][$profileOption['name']];
                    } else {
                        $uploadTarget = $config['upload_target']['default'];
                    }
                    $this->getInputFilter()->add([
                        'type' => FileInput::class,
                        'name' => $profileOption['name'],
                        'required' => false,
                        'filters'  => [
                            [
                                'name' => 'filerenameupload',
                                'options' => [
                                    'target' => $uploadTarget,
                                    'randomize' => true,
                                    'use_upload_extension' => true
                                ]
                            ]
                        ],                
                        'validators' => [
                            [
                                'name' => 'fileisimage'
                            ]
                        ]
                    ]);
                    break;
                default:
                    break 2;
            }
            $element->setLabel($profileOption['name']);
            $element->setAttribute('class', 'form-control');
            $this->add($element);
        }
        
        $submit = new Submit('save');
        $submit->setValue('save');
        $submit->setAttribute('class', 'btn btn-primary');
        $this->add($submit);
    }
}
