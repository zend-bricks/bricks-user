<?php

namespace ZendBricks\BricksUser\Controller;

use ZendBricks\BricksCommon\Controller\CrudController;
use ZendBricks\BricksUser\Api\UserApiInterface;
use Zend\Paginator\Paginator;
use ZendBricks\BricksUser\Model\ProfileOptionPaginatorAdapter;
use ZendBricks\BricksUser\Form\ProfileOptionForm;

class ProfileOptionController extends CrudController
{
    protected $api;
    protected $form;

    public function __construct(UserApiInterface $api)
    {
        $this->api = $api;
    }
    
    protected function getListPaginator()
    {
        $paginator = new Paginator(new ProfileOptionPaginatorAdapter($this->api));
        $paginator->setItemCountPerPage(self::ITEMS_PER_PAGE);
        return $paginator;
    }
    
    protected function getForm()
    {
        if (!$this->form instanceof ProfileOptionForm) {
            $this->form = new ProfileOptionForm();
        }
        return $this->form;
    }
    
    protected function save($data, $id = null)
    {
        $valueOptions = $this->getForm()->get('inputType')->getValueOptions();
        $data['inputType'] = $valueOptions[$data['inputType']];
        return $this->api->saveProfileOption($data, $id);
    }
    
    protected function getData($id)
    {
        $formData = $this->api->getProfileOptionData($id);
        $valueOptions = $this->getForm()->get('inputType')->getValueOptions();
        $formData['inputType'] = array_search($formData['inputType'], $valueOptions, true);
        return $formData;
    }
    
    protected function deleteById($id)
    {
        return $this->api->deleteProfileOption($id);
    }
    
    protected function getListRedirect()
    {
        return $this->redirect()->toRoute('profileoption/list');
    }
}
