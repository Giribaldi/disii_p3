<?php

class DataFiles extends ObjectModel
{
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'datafiles',
        'primary' => 'id_d atafiles',
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
}