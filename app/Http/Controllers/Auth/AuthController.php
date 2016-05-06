<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\PasswordReset;
use Validator, Crypt, Session, Hash, Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

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

    public function login() {
		return view('auth.login', $this->data);
	}
	
	public function verify_account(Request $request) {
		
		//get token from url
		$token = $request->input('token');
		$user = User::where('verification_token', $token)
					->where('is_active', 0)->get();
		
		//in case of empty result
		if($user->count() == 0) {
			
			$this->data['message'] = 'This verification link has already been used or is invalid.';
		} elseif ($user->count() > 1) {
			
			$this->data['message'] = 'Something went wrong. Please contact our support team.';
		} else {
			
			Session::put('is_password_set', true);
			Session::put('is_password_reset', false);
			Session::put('user', $user->toArray());
			return redirect('auth/change_password');
		}
		
		return view('errors.message', $this->data);
	}
	
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
			
			$user = User::where('id', $reset_request[0]->user_id)
						->where('is_active', 1)->get()->toArray();

			Session::put('is_password_reset', true);
			Session::put('is_password_set', false);
			Session::put('user', $user);
			return redirect('auth/change_password');
		}
		
		return view('errors.message', $this->data);
	}
	
	public function change_password(Request $request) {

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

	public function update_password(Request $request) {
		
		$data = $request->all();
		$response = array(
			'error' => false,
			'message' => ''
		);
		if(strlen($data['password']) < 8) {
			$response['error'] = true;
			$response['message']['password'][] = 'Password must be of minimum 8 characters length.';
		}
		
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
			
			$user = User::find(Session::get('user.0.id'))->toArray();

			if(!Hash::check($data['current_password'], $user['password'])) {
				
				$response['error'] = true;
				$response['message']['current_password'][] = 'Current password did not match the saved one.';
			}
		}
		
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
		
		$response['error'] = false;
		$response['message'] = 'Password updated successfully. You will be redirected.';

		//remove data from session related to set password, reset passsword
		Session::forget('is_password_set');
		Session::forget('is_password_reset');
		return response()->json($response);
	}
	
	public function validate_login(Request $request) {
		
		$email = $request->input('email');
		$password = $request->input('password');
		
		$user = User::where('email', $email)
					->where('is_active', 1)
					->get()->toArray();
					
		if(count($user)) {
			
			if(Hash::check($password, $user[0]['password'])) {
				Session::put('user', $user);
				Session::put('logged_in_user', true);
				return  response()->json(
					array(
						'error' => false,
						'message' => 'Login successful.'
					)
				);
			} else {
					
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
	
	function forgot_password(Request $request) {
		
		if ($request->isMethod('post')) {

			//check email exists in database
			$user = User::where('email', $request->input('email'))->get()->toArray();
			
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

	function logout() {
		Session::forget('logged_in_user');
		Session::forget('user');
		return redirect('auth/login');
	}
}
