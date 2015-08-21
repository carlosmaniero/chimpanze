<?php
class Model{
    protected $fields = [];
    protected $db;
    protected $table_name;

    function __construct($db){
        $this->db = $db;
    }

    function get_insert_fields(){
        $insert_fields = "";
        for($i=0; $i < count($this->fields); $i++){
            if($insert_fields != ""){
                $insert_fields = ",";    
            }
            $insert_fields .= $this->fields[$i];
        }
        return $insert_fields;
    }

    function get_prepared_fields(){
        $prepared_fields = "";
        for($i=0; $i < count($this->fields); $i++){
            if($prepared_fields != ""){
                $prepared_fields = ",";    
            }
            $prepared_fields .= ':' . $this->fields[$i];
        }
        return $prepared_fields;
    }
    function get_save_data(){
        $data = array();
        for($i=0; $i < count($this->fields); $i++){
            $key = ':' . $this->fields[$i];
            $data[$key] = $this->{$this->fields[$i]};
        }
        return $data;
    }

    function set_data($data){
        $keys = array_keys($data);
        for($i=0; $i < count($keys); $i++){
            $this->{$key} = $data[$key];
        }
    }

    function get_insert_sql(){
        $insert_fields = $this->get_insert_fields();
        $prepared = $this->get_prepared_fields();
        $sql = "INSERT INTO $this->table_name ($insert_fields) VALUES ($prepared)";
        return $sql;
    }

    function save(){
        $insert = false;
        if(empty($this->id)){
            $sql = $this->get_insert_sql();    
            $insert = true;
        }
        $query = $this->db->prepare($sql);
        $query->execute($this->get_save_data());
        if($insert)
            $this->id = $this->db->lastInsertId();
    }
}
