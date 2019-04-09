<?php
# /modules/disiigdpr/controllers/front/frontGDPRController.php

/**
 * Disii GDPR Module - A Prestashop Module
 *
 * Module RGPD dans le cadre du projet 3 de la formation DISII
 *
 * @author Giribaldi Raphael <giribaldiraphael@gmail.com>
 * @version 0.0.1
 */

if (!defined('_PS_VERSION_')) exit;

// You can now access this controller from /index.php?fc=module&module=disiigdpr&controller=frontGDPRController
class disiigdprhandleGDPRLinkmoduleFrontController extends ModuleFrontController
{

    public function __construct()
    {
        $this->secure_key = null;

        $this->isValid = false;
        $this->hash = null;



            if (Tools::getValue('status') == 1 || Tools::getValue('status') == 0) {
                $this->status = Tools::getValue('status');

            }
            if (Tools::getValue('user_id') && is_numeric(Tools::getValue('user_id'))) {
                $this->user_id = Tools::getValue('user_id');

            }
            if (Tools::getValue('token') && is_string(Tools::getValue('token'))) {
                $this->token = Tools::getValue('token');

            }
            if (Tools::getValue('datafile') && is_numeric(Tools::getValue('datafile'))) {
                $this->datafile = Tools::getValue('datafile');

            }

            if ($this->status !== null && $this->user_id !== null && $this->token !== null  && $this->datafile !== null) {
                $sql = "SELECT secure_key FROM " . _DB_PREFIX_ . "customer WHERE id_customer = " . $this->user_id;
                $this->secure_key = Db::getInstance()->executeS($sql);
                if(!empty($this->secure_key)){
                    $this->hash = md5($this->user_id . $this->status . $this->secure_key[0]['secure_key'].$this->datafile);
                }

                if ($this->token !== null && $this->hash !== null && $this->token == $this->hash) {

                    $this->isValid = true;
                }

            }





        parent::__construct();


    }

    public function initContent()
    {
        $this->context->smarty->assign(array(
            'datafile' => $this->datafile,
            'status' => $this->status,
            'isValid' => $this->isValid,
        ));
        $this->setTemplate('gdprHandleLink.tpl');
        parent::initContent();
    }

}

