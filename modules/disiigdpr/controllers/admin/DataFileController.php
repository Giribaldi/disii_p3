<?php

class DataFileController extends ModuleAdminController
{
    public $bootstrap = true;

    public function __construct()
    {

        $this->table = 'datafiles';
        $this->className = 'DataFiles';
        $this->lang = true;
        $this->fields_list = array(
            'id_datafiles' => array('title' => $this->l('ID'), 'align' => 'center', 'class' => 'fixed-width-xs'),
            'name' => array('title' => $this->l('Name')),
            'description' => array('title' => $this->l('Description'))
        );

        parent::__construct();
    }

    public function renderList()
    {
        //pour gérer la liste des actions en bout de ligne
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function renderForm()
    {
        $this->fields_form = array(
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Nom'),
                    'name' => 'name',
                    'required' => true,
                    'lang' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Description'),
                    'name' => 'description',
                    'required' => true,
                    'lang' => true,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button'
            )
        );
        return parent::renderForm();
    }





}