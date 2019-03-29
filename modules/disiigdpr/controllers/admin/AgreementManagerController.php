<?php

class AgreementManagerController extends ModuleAdminController
{
    public $bootstrap = true;

    public function __construct()
    {

        $this->table = 'agreement';
        $this->className = 'Agreement';
        $this->fields_list = array(
            'id_agreement' => array('title' => $this->l('id_agreement'), 'align' => 'center', 'class' => 'fixed-width-xs'),
            'id_customer' => array('title' => $this->l('Id_customer')),
            'last_name' => array('title' => $this->l('Last_Name')),
            'status' => array('title' => $this->l('Status')),
            'ip' => array('title' => $this->l('Ip_Address')),
            'date_add' => array('title' => $this->l('Date_Add'))
        );

        parent::__construct();
    }

    public function initToolbar() {
        parent::initToolbar();
        unset( $this->toolbar_btn['new'] );
    }

    public function renderList()
    {
        //pour gérer la liste des actions en bout de ligne
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $list = parent::renderList();

        $top = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'disiigdpr/views/templates/agreementfilter.tpl');

        return $top . $list;
    }

}