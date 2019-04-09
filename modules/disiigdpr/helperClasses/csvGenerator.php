<?php

/**
 * Simple class to output CSV data
 * Uses CollectionCore
 * @since 1.5
 */
class CSVGenerator
{
    public $filename;
    public $collection;
    public $delimiter;

    /**
     * Loads objects, filename and optionnaly a delimiter.
     * @param array|Iterator $collection Collection of objects / arrays (of non-objects)
     * @param string $filename : used later to save the file
     * @param string $delimiter Optional : delimiter used
     */
    public function __construct($collection, $filename, $delimiter = ';')
    {
        $this->filename = $filename;
        $this->delimiter = $delimiter;
        $this->collection = $collection;
    }

    /**
     * Main function
     * Adds headers
     * Outputs
     * @param array $arrayToUnset Optional : array off property to unset
     */
    public function export($arrayToUnset = [])
    {
        $unset = false;
        if(!empty($arrayToUnset)){
            $unset = true;
        }

        $this->headers();
        $header_line = false;

        foreach ($this->collection as $object) {
            if($unset){
                foreach ($arrayToUnset as $key){
                    unset($object->{$key});
                }
            }
            $user_id = $object->{'id_customer'};
            $data_file = $object->{'id_datafile'};
            $sql = "SELECT secure_key FROM "._DB_PREFIX_."customer WHERE id_customer = ".$user_id;
            $secure_key = Db::getInstance()->executeS($sql);
            $link= new Link();
            $link_gdpr_agreement = $link->getModuleLink('disiigdpr', 'handleGDPRLink');
            $object->acceptLink = $link_gdpr_agreement."&datafile=".$data_file."&checkLink=true&user_id=".$user_id."&status=1&token=". md5($user_id."1".$secure_key[0]['secure_key']);
            $object->denyLink = $link_gdpr_agreement."&datafile=".$data_file."&checkLink=true&user_id=".$user_id."&status=0&token=". md5($user_id."0".$secure_key[0]['secure_key']);


            $vars = get_object_vars($object);

            if (!$header_line) {
                $this->output(array_keys($vars));
                $header_line = true;
            }

            // outputs values
            $this->output($vars);
            unset($vars);

        }
    }




    /**
     * Wraps data and echoes
     * Uses defined delimiter
     */
    public function output($data)
    {
        $wraped_data = array_map(array('CSVCore', 'wrap'), $data);
        echo sprintf("%s\n", implode($this->delimiter, $wraped_data));
    }

    /**
     * Escapes data
     * @param string $data
     * @return string $data
     */
    public static function wrap($data)
    {
        $data = str_replace(array('"', ';'), '', $data);
        return sprintf('"%s"', $data);
    }

    /**
     * Adds headers
     */
    public function headers()
    {
        header('Content-type: text/csv');
        header('Content-Type: application/force-download; charset=UTF-8');
        header('Cache-Control: no-store, no-cache');
        header('Content-disposition: attachment; filename="'.$this->filename.'.csv"');
    }
}