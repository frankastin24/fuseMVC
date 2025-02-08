<?php 

class Model {
    private $fields;

    private $db;

    private $table;

    public function __construct($id = false)
    {
        global $db;

        $this->db = $db;

        $this->table = strtolower(static::class);

        if($id) {
            $this->getById($id);
        }
    }

    public function getById($id) {

        $fields = $this->db->getRowById($this->table, $id);
        
        foreach($fields as $label => $field) {
            $this->{$label} = $fields;
        }

    }

    public function findByField($field_name , $field_value) {
        
        $fields = $this->db->getWhereField($this->table,$field_name,$field_value);
        
        foreach($fields as $label => $field) {
            $this->{$label} = $fields;
        }

    }

    public function save() {

        $data = [];
        
        foreach($this->fields as $label) {
            $data[] = [$label , $this->{$label}];
        }

        $this->db->updateRow($this->table,$data);

    }

    
}