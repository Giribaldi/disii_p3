<?php

class DisiiGDPR extends Module
{

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

    public function getContent()
    {
        return 'je suis dans la config';
    }

    public function install()
    {
        return (parent::install() &&
            $this->installTab('AdminAdmin', 'AdminDisiiGDPR', 'GDPR Manager') &&
            Configuration::updateValue('DISIIGDPR', "DisiiGDPR") &&
            ppp('test')
        );
    }

    public function installDataFile()
    {
        
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
}
