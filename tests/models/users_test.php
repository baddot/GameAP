<?php

/**
 * @group Model
 */

class Users_test extends CIUnit_TestCase
{
    public function setUp()
    {
		$CI->load->model('users');
    }

    public function testProductFetching()
    {			
		$sql_data['reg_date'] 	= time();
		$sql_data['login'] 		= 'test';
		$sql_data['password'] 	= hash_password('password');
			
		$CI->users->add_user($sql_data);
		
		$CI->users->get_user_data(1);

        $this->assertEqual('test', $CI->users->user_data['login']);
        $this->assertEqual('password', $CI->users->user_data['password']);
    }
}
