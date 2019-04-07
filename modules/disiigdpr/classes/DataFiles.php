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
        $sqls = [];
        $sqls[] = 'INSERT IGNORE INTO`'._DB_PREFIX_.'datafiles`(`id_datafiles`, `date_add`, `description`)
                   VALUES (1, \'Accounting\', \'\')';
        $sqls[] = 'INSERT IGNORE INTO`'._DB_PREFIX_.'admin_gdpr_data_file`(`id_admin_gdpr_data_file`, `data_file_name`, `description`)
                   VALUES (2, \'Traffic statistics\', \'\')';
        $sqls[] = 'INSERT IGNORE INTO`'._DB_PREFIX_.'admin_gdpr_data_file`(`id_admin_gdpr_data_file`, `data_file_name`, `description`)
                   VALUES (3, \'Marketing history\', \'\')';
        $db = DB::getInstance();
        foreach($sqls as $sql){
            $db->execute($sql);
        }
        return true;
    }
}