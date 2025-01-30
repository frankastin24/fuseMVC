<?php

class DB {

    public $connection;

    function __construct() {

        global $db_server,$db_user,$db_password,$db_database;

        $this->connection = new mysqli($db_server, $db_user, $db_password, $db_database);

        if ($mysqli->connect_error) {
            die("Error: Could not connect to database. " . $mysqli->connect_error);
            //Display custom error page
        }

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