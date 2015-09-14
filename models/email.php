<?php
class Template extends Model{
    protected $fields = ['title', 'body'];
    protected $table_name = 'template';
}

class Email extends Model{
    protected $fields = ['title', 'template_id'];
    protected $table_name = 'email';
}

class Widget extends Model{
    protected $fields = ['key', 'type', 'email_id'];    
    protected $table_name = 'widget';
}

class WidgetData extends Model{
    protected $fields = ['key', 'data', 'widget_id'];    
    protected $table_name = 'widget_data';
}
