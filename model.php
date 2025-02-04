<?php 

class Model {
    public $fields;

    public $db;

    public function findByField($field_name , $field_value) {
        global $db;

        $this->db = $db;
        

    }

    
}