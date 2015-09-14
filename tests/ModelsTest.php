<?php
class EmailTest extends PHPUnit_Framework_TestCase{
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

    public function test_queryset(){
        $emails = Email::queryset($this->conn, 'SELECT * FROM email');
        $this->assertNotEquals(count($emails), 0);
    }

    public function test_delete_email(){
        $emails = Email::queryset($this->conn, 'SELECT * FROM email');
        
        foreach($emails as $email){
            $email->delete();    
        }

        $emails = Email::queryset($this->conn, 'SELECT * FROM email');
        $this->assertEquals(count($emails), 0);
    }    

    public function test_delete_template(){
        $templates = Template::queryset($this->conn, 'SELECT * FROM template');
        
        foreach($templates as $template){
            $template->delete();    
        }

        $templates = Template::queryset($this->conn, 'SELECT * FROM template');
        $this->assertEquals(count($templates), 0);
    }    
}
