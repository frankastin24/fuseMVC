<?php

class Database {

    public $connection;

    function __construct($db_server,$db_user,$db_password,$db_database) {

        $this->connection = new mysqli($db_server, $db_user, $db_password, $db_database);
        if ($this->connection->connect_error) {
            die("Error: Could not connect to database. " . $this->connection->connect_error);
        }

    }

    public function insert_row($table_name, $data) {

        $fields = '';
        $values = '';

        $fields_length = count($data) - 1;

        foreach($data as $index => $row) {

            $fields .= "`".$row[0]."` ". ($fields_length == $index ? '':',');

            $values .= (is_string($row[1]) ?  '"'.$row[1].'"' : $row[1]) . ($fields_length == $index ? '':',');

        }

        $this->connection->query("INSERT INTO ".$table_name." (".$fields.")
        VALUES (". $values .")");

        return $this->connection->insert_id;

    }

    public function getRowById($table_name, $id) {
       
        $sql = "SELECT from `". $table_name . "` WHERE id = " . $id . ";";

        $result = $this->connection->query($sql);

        return  $result->num_rows == 0 ? false : $result->fetch_assoc();

    }

    public function getWhereField($table_name,$field_name,$field_value,$comparsion = '=') {

        $sql = "SELECT * from `". $table_name . "` WHERE `".$field_name."` = " . (is_string($field_value) ? '"' . $field_value . '"' : $field_value). ";";

        $result = $this->connection->query($sql);

        $array = [];

        while($row =  $result->fetch_assoc()) {
            $array[] = $row;
        }

       

        return $array;
         
    }

    public function getWhere($table_name,$where_statement) {
        
        $sql = "SELECT * from `". $table_name . "` WHERE ".$where_statement. ";";

        $result = $this->connection->query($sql);

        $array = [];

        while($row =  $result->fetch_assoc()) {
            $array[] = $row;
        }

       

        return $array;
    }

    public function getAllRows($table_name,$limit = false,$offset = 0,$order = 'ASC') {

        $sql = "SELECT * from ". $table_name . " ORDER BY id DESC";
        $sql .= $limit ? ("LIMIT " . $limit) : ''; 

        $result = $this->connection->query($sql);

        $array = [];

        while($row =  $result->fetch_assoc()) {
            $array[] = $row;
        }

        return  $result->num_rows == 0 ? false : $array;

    }

    public function getLastRow($table_name) {
       
        $sql = "SELECT * FROM $table_name ORDER BY id DESC LIMIT 1;";
       
        $result = $this->connection->query($sql);

        return $result;

    }

    public function insert($table_name, $data) {

        $ids = [];

        if(is_array($data[0][0][0])) {

            foreach($data as $row) {
               $ids[] = $this->insert_row($table_name,$row);
            }
          
        } else {

            $ids = $this->insert_row($table_name,$data);

        }
 
        return $ids;

    }

    public function updateRow($table_name,  $data) {

        $sql = "UPDATE ".$table_name." SET ";
        
        $primary = array_shift($data);
       
        $fields_length = count($data) - 1;
        
        foreach($data as $index => $field) {
            $sql .= "`".$field[0]."` = '" . $field[1] . "' " . ($index == $fields_length ? ' ' : ',');
        }
        
        $sql .=  " WHERE ".$primary[0]." = ".$primary[1].";";
       
        $this->connection->query($sql);


    }

    public function create_table($table_name, $struture) {
        /*
          
          $table_name : String

          $struture = Multi Dimensional Array

          [
            [$field_name , $field_type, $is_primary]
          ]

        */

        $sql = "CREATE TABLE " . $table_name . " (";

        $fields_length = count($struture);

        foreach ($struture as $index => $field) {
            $sql .= $field[0];
            switch ($field[1]) {
                case 'number' : 
                   $sql .= " INT(6) UNSIGNED AUTO_INCREMENT " . (isset($field[2]) ? " PRIMARY KEY " : "");
                break;
                case 'text' :
                   $sql .= " VARCHAR(255)";
                break;
                case 'longText' :
                    $sql .= " TEXT";
                 break;
                default :
                   $sql .= $field[2];
                break;
            }

            $sql .= $index == ($fields_length - 1) ? ")" : ",";
        }
        
        $this->connection->query($sql);

    }



}

global $db;