<?php
/**
 * File contains AuthController class to handle authentication related requests
 *
 * @category Controller
 * @package  NewsStand
 * @author   Abani Meher <abanimeher@gmail.com>
 * @license  Copyright
 * @link     
 */

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\PasswordReset;
use Validator, Crypt, Session, Hash, Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Controller class to handle authentication related requests
 *
 * @extends  Controller
 * @category NewsStand
 * @package  Product
 * @author   Abani Meher <abanimeher@gmail.com>
 * @license  COPYRIGHT
 * @link     
 */
class AuthController extends Controller
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		parent::__construct();
    }

    /**
     * Handles signup request
     *
     * @param object $request
     * 
     * @return void
     */
    public function signup(Request $request) {

		//get required data from form post
		$data = array(
			'full_name' => $request->input('full_name'),
			'email' => $request->input('email')
		);

		//validate data
        $validator =  Validator::make($data, [
            'full_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users'
        ]);
        
        //if there are any validation error, show error message
        if($validator->fails()) {
			
			$messages = $validator->errors();
			return response()->json(
				array(
					'error' => true,
					'message' => $messages->toArray()
				)
			);
		} else {

			//generate a verification token
			$token = Crypt::encrypt(
				Crypt::encrypt(time()) . sha1(time() . rand() . $data['full_name'] . $data['email'])
			);
			$token = substr(str_rot13($token), 0, 255);

			//create user account
			$user =  User::create([
				'name' => $data['full_name'],
				'email' => $data['email'],
				'verification_token' => $token,
				'is_active' => 0,
				'signup_ip_address' => $request->ip()
			]);
			
			$verification_url = url('/auth/verify_account?token=' . $token);
			
			//send verification email
			Mail::send('emails.signup', [
					'user' => $user, 'url' => $verification_url
				], function ($mail) use ($user) {
				$mail->from(config('mail.from.address'), config('mail.from.name'));

				$mail->to($user->email, $user->name)->subject('NewsStand Signup Confirmation');
			});
			
			//send response
			return response()->json(
				array(
					'error' => false,
					'message' => 'Your account has been created successfully.' . 
								'An email has been sent to your email id.' . 
								'Please click on the  verification link in that email ' .
								'to verify your account and set password for your account.'
				)
			);
		}
	}

    /**
     * Shows login page
     *
     * @return object
     */
    public function login() {
		
		//load login view
		return view('auth.login', $this->data);
	}
	
    /**
     * Verifies account after verification  link is clicked in sign up confirmation mail
     *
     * @param object $request
     * 
     * @return object
     */
	public function verify_account(Request $request) {
		
		//get token from url
		$token = $request->input('token');
		
		//get user info using token
		$user = User::where('verification_token', $token)
					->where('is_active', 0)->get();
		
		//in case of empty result
		if($user->count() == 0) {
			
			$this->data['message'] = 'This verification link has already been used or is invalid.';
		} elseif ($user->count() > 1) {
			
			$this->data['message'] = 'Something went wrong. Please contact our support team.';
		} else {
			
			//set session and redirect to change password page
			Session::put('is_password_set', true);
			Session::put('is_password_reset', false);
			Session::put('user', $user->toArray());
			return redirect('auth/change_password');
		}
		
		return view('errors.message', $this->data);
	}
	
    /**
     * Verifies reset passsword request 
     *
     * @param object $request
     * 
     * @return object
     */
	public function verify_reset_password(Request $request) {
		
		//get token from url
		$token = $request->input('token');
		$reset_request = PasswordReset::where('token', $token)->get();		

		//in case of empty result
		if($reset_request->count() == 0) {
			
			$this->data['message'] = 'This verification link has already been used or is invalid.';
		} elseif ($reset_request->count() > 1) {
			
			$this->data['message'] = 'Something went wrong. Please contact our support team.';
		} else {
			
			//get user info
			$user = User::where('id', $reset_request[0]->user_id)
						->where('is_active', 1)->get()->toArray();
			
			//set data in session and redirect to change password page
			Session::put('is_password_reset', true);
			Session::put('is_password_set', false);
			Session::put('user', $user);
			return redirect('auth/change_password');
		}
		
		return view('errors.message', $this->data);
	}
	
    /**
     * shows change password page
     *
     * @return object
     */
	public function change_password() {
		
		//if required data is not found, show error message
		if(!Session::has('is_password_set') || 
			!Session::has('is_password_reset') ||
			!Session::has('user')) {

			$this->data['message'] = 'Invalid URL.';	
			return view('errors.message', $this->data);
		}
		
		//set required data in view from session
		$this->data['is_password_set'] = Session::get('is_password_set');
		$this->data['is_password_reset'] = Session::get('is_password_reset');
		$this->data['user_info'] = Session::get('user');
		$this->data['logged_in_user'] = Session::get('logged_in_user', false);
		
		return view('auth.change_password', $this->data);
	}

    /**
     * updates password of user
     *
     * @param object $request
     * 
     * @return object
     */
	public function update_password(Request $request) {
		
		//get all field data from request
		$data = $request->all();
		
		//ser default response
		$response = array(
			'error' => false,
			'message' => ''
		);
		
		//validate password field
		if(strlen($data['password']) < 8) {
			$response['error'] = true;
			$response['message']['password'][] = 'Password must be of minimum 8 characters length.';
		}
		
		//validate confirm password field
		if(strlen($data['confirm_password']) < 8) {
			$response['error'] = true;
			$response['message']['confirm_password'][] = 'Confirm password must be of minimum 8 characters length.';
		}
		
		if($data['password'] != $data['confirm_password']) {
			$response['error'] = true;
			$response['message']['confirm_password'][] = 'Password and confirm password did not match.';
		}
		
		//check if user is logged in
		if(Session::has('logged_in_user')) {
			
			//get user info from database
			$user = User::find(Session::get('user.0.id'))->toArray();
			
			//check with current password of user
			if(!Hash::check($data['current_password'], $user['password'])) {
				
				$response['error'] = true;
				$response['message']['current_password'][] = 'Current password did not match the saved one.';
			}
		}
		
		//in case of error, return response
		if($response['error']) {
			
			return response()->json($response);
		}
		
		//update user password
		$user = User::find(Session::get('user.0.id'));
		$user->password = Hash::make($data['password']);
		$user->is_active = 1;
		$user->verification_token = '';
		$user->verification_time = date('Y-m-d H:i:s');
		$user->verification_ip_address = $request->ip();
		$user->save();
		
		//set no error response and message
		$response['error'] = false;
		$response['message'] = 'Password updated successfully. You will be redirected.';

		//remove data from session related to set password, reset passsword
		Session::forget('is_password_set');
		Session::forget('is_password_reset');
		return response()->json($response);
	}
	
    /**
     * validates user login
     *
     * @param object $request
     * 
     * @return object
     */
	public function validate_login(Request $request) 
	{
		//get email and password from request
		$email = $request->input('email');
		$password = $request->input('password');
		
		//get user usingemail
		$user = User::where('email', $email)
					->where('is_active', 1)
					->get()->toArray();
		
		//if user is founf
		if(count($user)) {
			
			//check password saved in database with provided by user
			if(Hash::check($password, $user[0]['password'])) {
				
				//set session to indicate valid login
				Session::put('user', $user);
				Session::put('logged_in_user', true);
				
				//return response
				return  response()->json(
					array(
						'error' => false,
						'message' => 'Login successful.'
					)
				);
			} else {
				
				//return error response
				return  response()->json(
					array(
						'error' => true,
						'message' => 'Invalid email or password.'
					)
				);
			}
		} else {
			
			return  response()->json(
				array(
					'error' => true,
					'message' => 'Invalid email or password.'
				)
			);
		}
	}
	
	/**
     * shows forgot password page and process forgot password request
     *
     * @param object $request
     * 
     * @return object
     */
	function forgot_password(Request $request) {
		
		//if method is post, process request to update password
		if ($request->isMethod('post')) {

			//check email exists in database
			$user = User::where('email', $request->input('email'))->get()->toArray();
			
			//if user exists, create a token and send it in email to user
			if(count($user)) {
				
				$password_reset = new PasswordReset();
				$password_reset->user_id = $user[0]['id'];
				$password_reset->reset_request_ip = $request->ip();
				$password_reset->token = Hash::make(rand() . time() . $user[0]['id'] . $user[0]['verification_time']);
				$password_reset->save();
				
				$verification_url = url('/auth/verify_reset_password?token=' . $password_reset->token);

				//send verification email
				Mail::send('emails.password_reset', [
						'user' => $user, 'url' => $verification_url, 'ip' => $request->ip()
					], function ($mail) use ($user) {
					$mail->from(config('mail.from.address'), config('mail.from.name'));

					$mail->to($user['0']['email'], $user[0]['name'])->subject('NewsStand Password reset request');
				});
				
				$response['error'] = false;
				$response['message'] = 'A verification email has been sent to your email id. ' . 
										'Please click on the verification link to reset password.';
			} else {
				$response['error'] = true;
				$response['message'] = 'Sorry! there is no account associated with this email address.';
			}
			
			//send response
			return response()->json($response);
		} else {
			return view('auth.forgot_password', $this->data);
		}
	}

    /**
     * Logout user from session and show login page
     *
     * @return object
     */
	function logout() {
		
		//delete login related data
		Session::forget('logged_in_user');
		Session::forget('user');
		
		//redirect to login page
		return redirect('auth/login');
	}
}
