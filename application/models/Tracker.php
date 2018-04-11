<?php

class Genius_Model_Tracker
{
    public $url;
    public $ip;
    public $created_at;
    public $date;
    public $me;

    /**
     * Genius_Model_Traceur constructor.
     */
    public function __construct()
    {
        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        $this->date =  new DateTime();
        $this->me = '37.70.127.214';
        //$this->me = '192.168.1.16';
        $this->created_at = $this->date ->format('Y-m-d H:i:s');
    }

    public static function load()
    {
        return new self;
    }


    public  function track($module,$value,$spec)
    {

        if($this->ip <> $this->me){
            $fill=
                [
                    'ip' => $this->ip,
                    'url' => $this->url,
                    'created_at' => $this->created_at,
                    'module' => strtolower($module),
                    'value' => strtolower($value),
                    'spec' => strtolower($spec),
                ];
            try {
                Genius_Model_Global::insert('ec_searching', $fill);
            } catch (Zend_Exception $e) {
                var_dump($e);
            }
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