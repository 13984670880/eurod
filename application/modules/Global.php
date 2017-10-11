<?php

class Genius_Model_Global {

    public static function select($table_name, $champs = '', $where = 1) 
    {
        global $db;
        
        if ($champs != '')
            $sql = " SELECT $champs FROM $table_name WHERE $where ";
        else
            $sql = " SELECT * FROM $table_name WHERE $where ";

        $data = $db->fetchAll($sql);
        return $data;
    }
	
	public static function selectRow($table_name, $champs = '', $where = 1) 
    {
        global $db;
        
        if ($champs != '')
            $sql = " SELECT $champs FROM $table_name WHERE $where ";
        else
            $sql = " SELECT * FROM $table_name WHERE $where ";

        $data = $db->fetchRow($sql);
        return $data;
    }

    public static function insert($table, $data) 
    {
        global $db;
        try {
            $db->insert($table, $data);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function update($table, $data, $where) 
    {
        global $db;
        try {
            $db->update($table, $data, $where);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
	
    public static function query($sql) 
    {
        global $db;
        $data = $db->fetchAll($sql);
        return $data;
    }

    public static function execute($sql) 
    {
        global $db;
        try {
            $db->query($sql);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function delete($table, $where) 
    {
        global $db;
        try {
            $db->delete($table, $where);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function lastId()
    { 
        global $db;
        return $db->lastInsertId(); 
    }

    public static function updateOrderItem($table_name, $order_item)
    { 
        global $db;
        $sql  = "SELECT id, order_item FROM $table_name WHERE order_item>=$order_item";
        $data = $db->fetchAll($sql);

        if(!empty($data)){ 
            foreach ($data as $k => $item) {
                $id = $item['id'];
                $where = " id=$id ";
                Genius_Model_Global::update($table_name, array('order_item'=>$item['order_item']+1), $where);           
            } // endforeach     
        } // endif
    }

    public static function updateOrderItemCategory($table_name, $order_item, $id_category_group)
    { 
        global $db;
        $sql  = "SELECT id, order_item FROM $table_name WHERE order_item>=$order_item AND id_category_group=$id_category_group";
        $data = $db->fetchAll($sql);

        if(!empty($data)){ 
            foreach ($data as $k => $item) {
                $id = $item['id'];
                $where = " id=$id ";
                Genius_Model_Global::update($table_name, array('order_item'=>$item['order_item']+1), $where);           
            } // endforeach     
        } // endif
    }

    public static function updateOrderItemImage($table_name, $order_item, $id_item)
    { 
        global $db;
        $sql  = "SELECT id, order_item FROM $table_name WHERE id_item='$id_item' AND order_item>=$order_item";
        $data = $db->fetchAll($sql);

        if(!empty($data)){ 
            foreach ($data as $k => $item) {
                $id = $item['id'];
                $where = " id=$id ";
                Genius_Model_Global::update($table_name, array('order_item'=>$item['order_item']+1), $where);           
            } // endforeach     
        } // endif
    }

    public static function getMaxOrderItem($table_name)
    { 
        global $db;
        $sql  = "SELECT max(order_item) AS max FROM $table_name WHERE 1";
        $data = $db->fetchRow($sql);
        return $data['max'];  
    }

    public static function getMaxOrderItemCategory($table_name, $id_category_group)
    { 
        global $db;
        $sql  = "SELECT max(order_item) AS max FROM $table_name WHERE id_category_group=$id_category_group";
        $data = $db->fetchRow($sql);
        return $data['max'];  
    }

    public static function getMaxOrderItemImage($id_item)
    { 
        global $db;
        $sql  = "SELECT max(order_item) AS max FROM ".TABLE_PREFIX."images_relations WHERE id_item='$id_item'";
        $data = $db->fetchRow($sql);
        return $data['max'];   
    }
    
}