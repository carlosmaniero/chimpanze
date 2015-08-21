<?php
class EmailTest extends PHPUnit_Framework_TestCase{
    public function test_create_email(){
        $settings = $GLOBALS['settings'];
        $email = new Email(get_connection($settings)); 
        $email->title = 'Oi';
        $this->assertEquals($email->title, 'Oi');
        $email->save();
    }    
}
