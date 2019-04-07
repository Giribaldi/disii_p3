<?php
require_once(__DIR__ . '/classes/DataFiles.php');
require_once(__DIR__ . '/classes/Agreement.php');

class DisiiGDPR extends Module
{

    private $_default_values = array(
        'GDPR_FREQUENCY' => '25'
    );

    public function __construct()
    {
        /**
         * nom technique
         */
        $this->name = 'disiigdpr';
        /**
         * Nom publique
         */
        $this->displayName = 'Disii GDPR Module';
        /**
         * Catégorie du Module
         */
        $this->tab = 'administration';

        /**
         * Version
         */
        $this->version = '0.0.1';
        /**
         * Auteur
         */
        $this->author = 'Raphael Giribaldi';
        /**
         * Description du Module
         */
        $this->description = 'Module RGPD dans le cadre du projet 3 de la formation DISII';
        /**
         * Instance des modules, sur 0 empêche l'instance du module au chargement de la liste et créer le fichier config.xml
         */
        $this->need_instance = 0;


        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        /**
         * est-ce que votre module hérite du look de prestashop
         */
        $this->bootstrap = true;



        parent::__construct();


    }


    public function install()
    {
        return (parent::install()
            && $this->_initDefaultConfigurationValues()
            && $this->_installSQL()
            && $this->installTab('DEFAULT', 'AdminDisiiGDPR', 'GDPR Manager')
            && $this->installTab('AdminDisiiGDPR', 'DataFile', 'File manager')
            && $this->installTab('AdminDisiiGDPR', 'AgreementManager', 'Agreement Manager')
            && $this->registerHook('displayBackOfficeHeader')
            && $this->registerHook('customerAccount')
        );
    }

    private function _initDefaultConfigurationValues()
    {
        foreach ($this->_default_values as $key => $value) {
            if (false === Configuration::get($key)) {
                Configuration::updateValue($key, $value);
            }
        }
        return true;
    }

    public function installTab($parent_class, $class_name, $name)
    {
        $tab = new Tab();
        // Define the title of your tab that will be displayed in BO
        $tab->name[$this->context->language->id] = $name;
        // Name of your admin controller
        $tab->class_name = $class_name;
        // Id of the controller where the tab will be attached
        // If you want to attach it to the root, it will be id 0 (I'll explain it below)
        $tab->id_parent = (int)Tab::getIdFromClassName($parent_class);


        // Name of your module, if you're not working in a module, just ignore it, it will be set to null in DB
        $tab->module = $this->name;
        // Other field like the position will be set to the last, if you want to put it to the top you'll have to modify the position fields directly in your DB
        return $tab->add();
    }

    public function installDataFile()
    {

    }

    private function _installSQL()
    {
        $sqls = [];

        $sqls[] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."datafiles` (
            `id_datafiles` int(11) NOT NULL AUTO_INCREMENT,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NOT NULL,
            PRIMARY KEY (`id_datafiles`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $sqls[] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."datafiles_lang` (
            `id_datafiles` int(11) NOT NULL AUTO_INCREMENT,
            `id_lang` int(11) NOT NULL,
            `name` varchar(64) NOT NULL,
            `description` text NOT NULL,
            PRIMARY KEY (`id_datafiles`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $sqls[] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."agreement` (
            `id_agreement` int(11) NOT NULL AUTO_INCREMENT,
            `id_customer` int(11) NOT NULL,
            `status` int(11) NOT NULL,
            `ip` varchar(30) NOT NULL,
            `date_add` datetime NOT NULL,
            `id_datafile` int(11) NOT NULL,
            PRIMARY KEY (`id_agreement`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $db = DB::getInstance();

        foreach($sqls as $sql)
        {
            $db->execute($sql);
        }
        return true;
    }


    public function uninstall()
    {
        // Uninstall Tabs
        $tab = new Tab((int)Tab::getIdFromClassName('AdminDisiiGDPR'));
        $tab->delete();

        // Uninstall Module
        if (!parent::uninstall())
            return false;
        return true;
    }

    public function getContent()
    {
        $html = '';
        $html .= $this->processConfiguration();
        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('GDPR configuration')
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Acceptance proof frequency'),
                    'name' => 'GDPR_FREQUENCY',
                    'required' => true
                ]
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]
        ];
        $helper = new HelperForm();
        // Module, token and currentIndex
        $helper->module = $this;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                    '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];
        // Load current value
        foreach ($this->_default_values as $key => $default_value) {
            $helper->fields_value[$key] = Configuration::get($key);
        }
        $html .= $helper->generateForm($fieldsForm);
        return $html;
    }

    public function processConfiguration()
    {
        if(Tools::isSubmit('submitgdpr')){
            $set_day = Tools::getValue('GDPR_FREQUENCY');
            Configuration::updateValue('GDPR_FREQUENCY', $set_day);
        }
    }



    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addCss($this->_path . 'views/css/tab.css');
    }

    public function hookCustomerAccount(){

        //return $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'disiigdpr/views/templates/front/gdprAccountButton.tpl');
        $link= new Link();
        $link_gdpr_agreement = $link->getModuleLink($this->name, 'displayGDPR');
        $this->context->smarty->assign(array(
           'gdprAgreementLink' => $link_gdpr_agreement
        ));
        
        return $this->display(__FILE__, 'gdprAccountButton.tpl');
    }
}
