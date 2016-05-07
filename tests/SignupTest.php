<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SignupTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public $email;
    public function testExample()
    {
        $this->assertTrue(true);
    }
    
    public function testValidSignup()
    {
		$this->json('POST', '/auth/signup', ['full_name' => 'Abani Meher', 'email' => 'crossovertest' . time() . '@mailinator.com'])
			->seeJson([
				 'error' => false,
				 'message' => 'Your account has been created successfully.An email has been sent to your email id.Please click on the  verification link in that email to verify your account and set password for your account.'
			 ]);    
	}
	
    public function testDuplicateEmailSignup()
    {
		$email = 'crossovertest' . time() . '@mailinator.com';
		$this->json('POST', '/auth/signup', ['full_name' => 'Abani Meher', 'email' => $email])
			->seeJson([
				 'error' => false,
				 'message' => 'Your account has been created successfully.An email has been sent to your email id.Please click on the  verification link in that email to verify your account and set password for your account.'
			 ]);    

		$this->json('POST', '/auth/signup', ['full_name' => 'Abani Meher', 'email' => $email])
			->seeJson([
				 'error' => true,
				 'message' => [
					'email' => ['The email has already been taken.']
				 ]
			 ]);    
	}
	
    public function testFieldRequired()
    {
		$this->json('POST', '/auth/signup', ['full_name' => '', 'email' => ''])
			->seeJson([
				 'error' => true,
				 'message' => [
					'email' => ['The email field is required.'],
					'full_name' => ['The full name field is required.']
				 ]
			 ]);    
	}
	
    public function testInvalidEmail()
    {
		$this->json('POST', '/auth/signup', ['full_name' => 'Test Name', 'email' => 'invalidemail'])
			->seeJson([
				 'error' => true,
				 'message' => [
					'email' => ['The email must be a valid email address.'],
				 ]
			 ]);    
	}
}
