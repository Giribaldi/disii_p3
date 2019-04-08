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
class disiigdprDisplayGDPRModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        parent::__construct();
        // Do your stuff here
    }

    public function lostBasket(){
        $user_id = Context::getContext()->customer->id;
        $sql = "SELECT COUNT(id_cart)
		FROM "._DB_PREFIX_."cart
        WHERE id_customer = ".$user_id." AND NOT EXISTS (SELECT 1 FROM "._DB_PREFIX_."orders WHERE "._DB_PREFIX_."orders.id_cart = "._DB_PREFIX_."cart.id_cart)";
        return Db::getInstance()->executeS($sql);
    }
    public function orders(){

        $user_id = Context::getContext()->customer->id;
        $sql = "SELECT COUNT(id_order)
            FROM "._DB_PREFIX_."orders
            WHERE id_customer = ".$user_id;
        return Db::getInstance()->executeS($sql);
    }

    public function getVisits()
    {
        $user_id = Context::getContext()->customer->id;
        return Db::getInstance()->executeS('
		SELECT COUNT(c.id_connections)
		FROM `'._DB_PREFIX_.'guest` g
		LEFT JOIN `'._DB_PREFIX_.'connections` c ON c.id_guest = g.id_guest
		WHERE g.`id_customer` = '.(int)$user_id);
    }

    public function getAddresses()
    {
        $user_id = Context::getContext()->customer->id;
        return Db::getInstance()->executeS('
		SELECT COUNT(id_address)
		FROM `'._DB_PREFIX_.'address` 
		WHERE `id_customer` = '.(int)$user_id);
    }

    public function initContent()
    {
        $sql_query_data_files = 'SELECT * FROM '._DB_PREFIX_.'datafiles AS A LEFT JOIN '._DB_PREFIX_.'datafiles_lang AS B ON A.id_datafiles = B.id_datafiles';
        $data_files = Db::getInstance()->executeS($sql_query_data_files);
        $user_id = Context::getContext()->customer->id;
        $data_files_agreements = [];
        foreach($data_files as $file){
            $sql_query_agreement = "SELECT status FROM "._DB_PREFIX_."agreement WHERE id_customer = '".$user_id."' AND id_datafile = '".$file['id_datafiles']."' ORDER BY id_agreement DESC LIMIT 1";
            $agreements = Db::getInstance()->executeS($sql_query_agreement);
            if($agreements) {
                $data_files_agreements[$file['id_datafiles']] = $agreements[0]['status'];
            }
        }

        $lost_basket = $this->lostBasket();
        $orders = $this->orders();
        $visits = $this->getVisits();
        $addresses = $this->getAddresses();

        $this->context->smarty->assign([
            'greetingsFront' => 'Hello Front from Disii GDPR Module !',
            'data_files' => $data_files,
            'agreements' => $data_files_agreements,
            'lost_basket' => $lost_basket[0]["COUNT(id_cart)"],
            'orders' => $orders[0]["COUNT(id_order)"],
            'visits' => $visits[0]["COUNT(c.id_connections)"],
            'addresses'=> $addresses[0]["COUNT(id_address)"],
        ]);

        $this->setTemplate('gdprFrontTemplate.tpl');
        // Don't forget to create /modules/disiigdpr/views/templates/front/my-front-template.tpl

        parent::initContent();
    }
    public function postProcess() {
        if (Tools::isSubmit('submit_gdpr_agreement')){
            $user_id = Context::getContext()->customer->id;
            $ip = $_SERVER['REMOTE_ADDR'];

            foreach ($_POST as $key => $value){
                if ($key != 'submit_gdpr_agreement') {
                    $sql = "INSERT INTO "._DB_PREFIX_."agreement (id_customer, status, ip, date_add, id_datafile)
                                VALUES (".$user_id.", '".$value."', '".$ip."', NOW(),'".$key."')" ;
                    $db = DB::getInstance();
                    $db->execute($sql);
                }
            }
        }
    }
}

