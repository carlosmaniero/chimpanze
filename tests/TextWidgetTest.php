<?php
class TextWidgetTest extends PHPUnit_Framework_TestCase{
    private $conn;
    private $settings;

    protected function setUp(){
        $this->settings = $GLOBALS['settings'];
        $this->conn = get_connection($this->settings);
    }

    public function test_create_email(){
        // Creation teste
        $template = new Template($this->conn);
        $template->title = 'foo';
        $template->body = 'bar';
        $template->save();

        $email = new Email($this->conn); 
        $email->title = 'foo';
        $email->template_id = $template->id;
        $this->assertEquals($email->title, 'foo');

        $email->save();
        $this->assertNotEquals($email->id, null);

    }

    public function test_create_widget(){
        global $widgets_registration; 
        $widget_class = $widgets_registration->get('text');
        $email = Email::queryset($this->conn, 'SELECT * FROM email order by id desc limit 1')[0];

        $widget = new $widget_class($this->conn, $email, 'coder');
        $widget->set_data('text', 'Carlos Maniero');
        $widget->save();
    }
}
