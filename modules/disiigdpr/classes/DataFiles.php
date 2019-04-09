<?php

class DataFiles extends ObjectModel
{
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'datafiles',
        'primary' => 'id_datafiles',
        'multilang' => true,
        'fields' => array(
            'date_add' => array('type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDate'),
            'date_upd' => array('type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDate'),
            //Translatable
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'required' => true, 'size' => 64),
            'description' => array('type' => self::TYPE_STRING, 'lang' => true, 'required' => true, 'size' => 255),
        )
    );
    public $id_datafiles;
    public $name;
    public $description;
    public $date_add;
    public $date_upd;

    public static function defaultSQL(){

        $languages = Language::getLanguages();

        $sqls = [];

        $sqls[] = "INSERT IGNORE INTO`"._DB_PREFIX_."datafiles`(`id_datafiles`, `date_add`, `date_upd`)
                   VALUES (1, NOW(), NOW())";
        $sqls[] = "INSERT IGNORE INTO`"._DB_PREFIX_."datafiles`(`id_datafiles`, `date_add`, `date_upd`)
                   VALUES (2, NOW(), NOW())";
        $sqls[] = "INSERT IGNORE INTO`"._DB_PREFIX_."datafiles`(`id_datafiles`, `date_add`, `date_upd`)
                   VALUES (3, NOW(), NOW())";

        foreach ($languages as $lang){

            $sqls[] = "INSERT IGNORE INTO`"._DB_PREFIX_."datafiles_lang`(`id_datafiles`, `id_lang`, `name`, `description`)
                   VALUES (1,".$lang['id_lang'].", 'Comptabilité', 'Fichier de données composé de vos factures permettant de tenir une trace comptable' )";
            $sqls[] = "INSERT IGNORE INTO`"._DB_PREFIX_."datafiles_lang`(`id_datafiles`, `id_lang`, `name`, `description`)
                   VALUES (2, ".$lang['id_lang'].", 'Statistiques de visite', 'Fichier de données composé d\'un historique de vos visite sur le site' )";
            $sqls[] = "INSERT IGNORE INTO`"._DB_PREFIX_."datafiles_lang`(`id_datafiles`, `id_lang`, `name`, `description`)
                   VALUES (3,".$lang['id_lang'].", 'Historique marketing', 'Fichier de donnée composé de vos diferentes adresses ainsi que vos paniers' )";
        }

        $db = DB::getInstance();
        foreach($sqls as $sql){
            $db->execute($sql);
        }
        return true;
    }
}