<?php
/**
 * File contains DashboardController class to handle NewsStand dashboard related requests
 *
 * @category Controller
 * @package  NewsStand
 * @author   Abani Meher <abanimeher@gmail.com>
 * @license  Copyright
 * @link     
 */

namespace App\Http\Controllers\News;

use App\Models\User;
use App\Models\News;
use Validator, Crypt, Session, Hash, Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

/**
 * Controller class to handle NewsStand dashbaord related requests
 *
 * @extends  Controller
 * @category NewsStand
 * @package  Product
 * @author   Abani Meher <abanimeher@gmail.com>
 * @license  COPYRIGHT
 * @link     
 */
class DashboardController extends Controller
{
    /**
     * Create a new dashboard controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		parent::__construct();
    }
    
    /**
     * Show user dashboard to add new and see last 5 published news
     *
     * @return object
     */
    public function index() 
    {
		//collect last 5 news posted by user
		$news = News::where('created_by' , Session::get('user.0.id'))
					->orderBy('id', 'desc')->take(5)->get()->toArray();
		$this->data['latest_news'] = $news;
		
		//set data in view
		$this->data['news_error'] = Session::get('news_error', false);
		$this->data['news_message'] = Session::get('news_message', false);
		$this->data['form_data'] = Session::get('form_data', false);
		$this->data['message'] = $this->data['news_error'] ? $this->data['news_error']['message'] : false;

 		//remove data from session
		Session::forget('news_error');
		Session::forget('news_message');
		Session::forget('form_data');
		
		return view('news.dashboard', $this->data);
	}
}
