<?php
class BaseWidget{
    protected $object;
    protected $email;
    protected $db;
    protected $model = 'Widget';
    protected $model_data = 'WidgetData';
    protected $keys = [];

    function __construct($db, $email, $key, $object=null){
        $this->data = [];
        $this->db = $db;
        $this->email = $email;
        $class = $this->get_widget_model();

        if($object == null){
            $this->object = new $class($db);
        }else{
            $this->object = $object;    
        }

        $this->object->key = $key;
    }

    public function get_widget_model(){
        return $this->model;    
    }

    public function get_widget_data_model(){
        return $this->model_data;    
    }

    public function get_keys(){
        return $this->keys;    
    }

    public function load_data(){
        if($this->object->id != null){
            $sql = 'SELECT * FROM widget_data WHERE id=' . $this->object->id;
            $class = $this->get_widget_data_model();
            $datas = $class::queryset($this->db, $sql);

            foreach($datas as $data){
                if(in_array($data->key, $this->get_keys())){
                    $this->data[$data->key] = $data;
                }else{
                    // Delete if isn't used
                    $data->delete();    
                }
            }
        }

        // Complete all keys
        foreach($this->get_keys() as $key){
            if(!array_key_exists($key, $this->data)){
                $this->data[$key] = new WidgetData($this->db);
                $this->data[$key]->key = $key;
            }
        }
    }

    public function save_data(){
        foreach($this->data as $key => $data){
            $data->widget_id = $this->object->id;
            $data->key = $key;
            $data->save();
        }    
    }

    public function save($with_data=true){
        $this->object->type = $this->get_type();
        $this->object->key = $this->get_key();
        $this->object->email_id = $this->email->id;
        $this->object->save();

        if($with_data)
            $this->save_data();
    }

    public function get_type(){
        return get_class($this);
    }

    public function get_key(){
        return $this->object->key;    
    }

    public function set_data($key, $data){
        if(in_array($key, $this->get_keys())){
            if(!array_key_exists($key, $this->data)){
                $class = $this->get_widget_data_model();
                $this->data[$key] = new $class($this->db);
            }
            $this->data[$key]->data = $data;
        }else{
            throw new WidgetNotKeyException("A chave '$key' mão existe no widget $this->type");
        }
    }
}

class WidgetRegistration{
    private $widgets = [];

    public function register($key, $widget_class){
        $this->widgets[$key] = $widget_class;    
    }

    public function get($key){
        return $this->widgets[$key];    
    }

    public function all(){
        return $this->widgets;    
    }
}
