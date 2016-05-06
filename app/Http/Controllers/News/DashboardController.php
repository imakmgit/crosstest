<?php

namespace App\Http\Controllers\News;

use App\Models\User;
use App\Models\News;
use Validator, Crypt, Session, Hash, Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class DashboardController extends Controller
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

    /**
     * Create a new dashboard controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		parent::__construct();
    }
    
    public function index() 
    {
		//collect last 5 news posted by user
		$news = News::where('created_by' , Session::get('user.0.id'))
					->orderBy('id', 'desc')->take(5)->get()->toArray();
		$this->data['latest_news'] = $news;
		
		//set data in view
		$this->data['news_error'] = Session::has('news_error') ? Session::get('news_error') : false;
		$this->data['news_message'] = Session::has('news_message') ? Session::get('news_message') : false;
		$this->data['form_data'] = Session::has('form_data') ? Session::get('form_data') : false;
		$this->data['message'] = $this->data['news_error'] ? $this->data['news_error']['message'] : false;
		
 		//remove data from session
		Session::forget('news_error');
		Session::forget('news_message');
		Session::forget('form_data');
		
		return view('news.dashboard', $this->data);
	}
}
