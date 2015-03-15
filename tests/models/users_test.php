<?php

/**
 * @group Model
 */

class Users_test extends CIUnit_TestCase
{
    public function setUp()
    {
		$this->CI->load->model('users');
    }

    public function testProductFetching()
    {			
		$sql_data['reg_date'] 	= time();
		$sql_data['login'] 		= 'test';
		$sql_data['password'] 	= hash_password('password');
			
		$this->assertTrue($this->CI->users->add_user($sql_data));
		
		$this->assertTrue($this->CI->users->get_user_data(1));

        $this->assertEquals('test', $this->CI->users->user_data['login']);
        $this->assertEquals('password', $this->CI->users->user_data['password']);
    }
}
