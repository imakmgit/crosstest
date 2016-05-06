<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testValidSignup()
    {
			$this->json('POST', '/auth/signup', ['full_name' => 'Sally', 'email' => 'abani.mehe.r@gmail.com'])
						->seeJson([
							 'error' => false,
							 'message' => 'Your account has been created successfully.An email has been sent to your email id.Please click on the  verification link in that email to verify your account and set password for your account.'
						 ]);    
	}
	
    public function testInvalidSignup()
    {
			$this->json('POST', '/auth/signup', ['full_name' => 'Sally', 'email' => 'abani.mehe.r@gmail.com'])
						->seeJson([
							 'error' => true,
						 ]);    
	 }
}
