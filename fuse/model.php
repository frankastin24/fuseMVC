<?php 

class Model {
    private $db;


    public function __construct($id = false)
    {
        global $db;

        $this->db = $db;

        if($id) {
            $this->getByPrimary($id);
        }
    }

    public function getById($id) {

        $result = $this->db->getRowById($this->table, $id);
        
        foreach($this->fields as $label) {
            $this->{$label} = $result[$label];
        }

    }

    public function getAll() {

        $result = $this->db->getAllRows($this->table);

        if(!$result) return [];

        $array = [];

        $class_name = static::class;


        foreach($result as $index => $row) {
             
             $array[$index] = new $class_name();
             
             foreach($this->fields as $field) {
                $array[$index]->{$field} = $row[$field];
             }
            
        }

        return $array;

    }

    public function getWhere($where_statement) {

        $result = $this->db->getWhere($this->table,$where_statement);
        
        $array = [];
        
        $class_name = static::class;

        foreach($result as $index => $row) {
                
                $array[$index] = new $class_name();
                
                foreach($this->fields as $field) {
                $array[$index]->{$field} = $row[$field];
                }
            
        }

        return $array;


    }

    public function getLast() {
        $result =  $this->getLast();
        foreach($this->fields as $field) {
           $this->{$field} = $result[$field];
        }
    }

    public function getWhereField($field_name , $field_value) {
        
        $result = $this->db->getWhereField($this->table,$field_name,$field_value);
      
        $array = [];
        
        $class_name = static::class;

        foreach($result as $index => $row) {
                
                $array[$index] = new $class_name();
                
                foreach($this->fields as $field) {
                $array[$index]->{$field} = $row[$field];
                }
            
        }

        return $array;

        

       

    }

    public function hasOne($model_name) {
        
        $model = new $model_name();

       $array = $model->getWhereField(strtolower(static::class.'_id') , $this->{$this->fields[0]} );

        $this->{strtolower($model_name)} = count($array) ? $array[0] : false;

    }

    public function isOne($model_name) {
        
        $model = new $model_name();

        $model->getByPrimary($this->{strtolower($model_name.'_id')} );

        $this->{strtolower($model_name)} = $model;

    }

    public function hasMany($model_name) {

        $model = new $model_name();

        $array = $model->getWhereField(strtolower(static::class.'_id') , $this->{$this->fields[0]} );
 
        $this->{strtolower($model_name)} = count($array) ? $array : false;

    }

    public function getByPrimary($value) {
        
        $result = $this->db->getWhereField($this->table,$this->fields[0],$value);
        
        if(!$result) return false;

        $array = [];
             
        foreach($this->fields as $field) {
            $this->{$field} = $result[0][$field];
        }

        return true;

    }

    public function save() {

        
        if(isset($this->{$this->fields[0]})) {
           
            $data = [];
        
            foreach($this->fields as $index => $label) {
              
                $data[] = [$label , $this->{$label}];
            }
    
            $this->db->updateRow($this->table,$data);
            
        } else {
            
            $data = [];
        
            foreach($this->fields as $label) {
                if($this->fields[0] != $label) {
                    $data[] = [$label , $this->{$label}];
                }
                
            }
    
            $this->{$this->fields[0]} = $this->db->insert_row($this->table,$data);

        }

       

    }

    
}