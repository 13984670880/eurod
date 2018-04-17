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


    public  function track($module,$value,$spec,$expression=null)
    {

        if($this->ip <> $this->me){
            $fill=
                [
                    'ip' => $this->ip,
                    'url' => $expression == null ? $this->url : $expression,
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

    public static function find($ip){

        global $db ;

        $ip = strval($ip);

        $sql = $db
            ->select()
            ->from('ec_searching')
            ->where("ec_searching.ip = \"$ip\"")
            ->order('ec_searching.created_at DESC')
        ;

        $results = $db->query($sql)->fetchall();

        return  $results;
    }

    public function select($id = null){

        global $db ;

        $sql = $db
            ->select()
            ->from('ec_searching')
            ->order('ec_searching.created_at DESC')
        ;

        if($id <> null )
        {
            $sql = $sql->where("ec_filtres_terminal .product_id = $id");
        }

        return  $sql;
    }

    public function filter($search = null){

        global $db ;

        $sql = $db
            ->select()
            ->from('ec_searching')
            ->order('ec_searching.created_at DESC')
        ;
        if($search == 'produits'){
            $sql = $sql->where("ec_searching.module = \"page_product\"");
        }
        elseif($search == 'devis'){
            $sql = $sql->where("ec_searching.value = \"devis\"");
        }
        elseif($search == 'modules'){
            $sql = $sql->where("ec_searching.value = \"general\"");
        }
        elseif($search == 'configurateur'){
            $sql = $sql
                ->where("ec_searching.value = \"configurateur\"")
                ->where("ec_searching.module = \"widget\"")
            ;
        }

        //print_r($sql->__ToString());
        //die();

        return $result = $db->query($sql)->fetchAll();
    }

    public function getInterval()
    {
        global $db ;

        $sql = $db
            ->select()
            ->from('ec_searching')
            ->order('ec_searching.created_at DESC')
        ;

        //print_r($sql->__ToString());
        //die();

        return $result = $db->query($sql)->fetchAll();
    }
}