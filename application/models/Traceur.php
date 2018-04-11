<?php

class Genius_Model_Traceur
{

    public static function track($ip,$module,$search,$value=null)
    {
        $date = new DateTime();

        $fill=
            [
                'ip' => $ip,
                'module' => $module,
                'value' => $value,
                'search' => $search,
                'url' => '$url',
                'created_at' => $date->format('Y-m-d H:i:s') ,
            ];
        try {
            Genius_Model_Global::insert('ec_searching', $fill);
        } catch (Zend_Exception $e) {
            var_dump($e);
        }

    }

    public static function all(){
        global $db ;

        $sql = $db
            ->select()
            ->from('ec_searching')
            ->order('ec_searching.created_at DESC')
        ;
        return $sql;
    }

}