<?php
class Model{
    protected $fields = [];
    protected $db;
    protected $table_name;
    public $id = null;

    function __construct($db){
        $this->db = $db;
    }

    function get_table(){
        return $this->table_name;    
    }

    function get_list_fields(){
        $insert_fields = "";
        for($i=0; $i < count($this->fields); $i++){
            if($insert_fields != ""){
                $insert_fields .= ",";    
            }
            $insert_fields .= '`' . $this->fields[$i] . '`';
        }
        return $insert_fields;
    }

    function get_prepared_fields(){
        $prepared_fields = "";
        for($i=0; $i < count($this->fields); $i++){
            if($prepared_fields != ""){
                $prepared_fields .= ",";
            }
            $prepared_fields .= ':' . $this->fields[$i];
        }
        return $prepared_fields;
    }
    function get_save_data(){
        $data = array();
        for($i=0; $i < count($this->fields); $i++){
            $key = ':' . $this->fields[$i];
            $data[$key] = $this->db->quote($this->{$this->fields[$i]});
        }
        return $data;
    }

    function set_data($data){
        $keys = array_keys($data);
        for($i=0; $i < count($keys); $i++){
            $key = $keys[$i];
            $this->{$key} = $data[$key];
        }
    }

    function get_insert_sql(){
        $insert_fields = $this->get_list_fields();
        $prepared = $this->get_prepared_fields();
        $sql = "INSERT INTO $this->table_name ($insert_fields) VALUES ($prepared)";
        return $sql;
    }

    function get_update_set_sql(){
        $update_fields = "";
        for($i=0; $i < count($this->fields); $i++){
            if($update_fields != ""){
                $update_fields .= ",";    
            }
            $update_fields .= '`' . $this->fields[$i] . '` = ' . $this->db->quote($this->{$this->fields[$i]}) . '';
        }
        return $update_fields;
    }

    function get_update_sql(){
        return "UPDATE $this->table_name SET " . $this->get_update_set_sql() . " WHERE id=$this->id";
    }

    function save(){
        $insert = false;
        if(empty($this->id)){
            $sql = $this->get_insert_sql();    
            $insert = true;
        }else{
            $sql = $this->get_update_sql();
        }
        $query = $this->db->prepare($sql);
        $query->execute($this->get_save_data());
        if($insert){
            $this->id = $this->db->lastInsertId();
        }
    }

    function get($id){
        $id = (int) $id;
        $this->id = $id;
        $fields = $this->get_list_fields();
        $query = $this->db->query("SELECT $fields from $this->table_name where id=$id");
        $this->set_data($query->fetch(PDO::FETCH_ASSOC));
    }

    function get_delete_sql(){
        return "DELETE FROM $this->table_name where id=$this->id";
    }

    function delete(){
        $query = $this->db->prepare($this->get_delete_sql());
        $ret = $query->execute();
        $this->id = null;
        return $ret;
    }

    static function queryset($db, $sql){
        $query = $db->query($sql);
        return static::to_queryset($db, $query);
    }

    static function to_queryset($db, $query){
        $queryset = array();
        $i = 0;
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $queryset[$i] = new static($db);
            $queryset[$i]->set_data($row);
            $i++;
        }
        return $queryset;
    }
}
