<?php

class Agreement extends ObjectModel
{
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'agreement',
        'primary' => 'id_agreement',
        'fields' => array(
            'id_customer' => array('type' => self::TYPE_INT),
            'status' => array('type' => self::TYPE_INT,),
            'ip' => array('type' => self::TYPE_STRING),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'id_datafile' => array('type' => self::TYPE_INT),
        )
    );
    public $id_agreement;
    public $id_customer;
    public $id_datafile;
    public $status;
    public $ip;
    public $date_add;


}