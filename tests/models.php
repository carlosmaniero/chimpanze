<?php
class EmailTest extends PHPUnit_Framework_TestCase{
    public function test_create_email(){
        $settings = $GLOBALS['settings'];
        $email = new Email(get_connection($settings)); 
        $email->title = 'Oi';
        $this->assertEquals($email->title, 'Oi');
        $email->save();
        $this->assertNotEquals($email->id, null);

        $email2 = new Email(get_connection($settings));
        $email2->get($email->id);
        $this->assertEquals($email->title, $email2->title);
        $email2->delete();
    }    
}
